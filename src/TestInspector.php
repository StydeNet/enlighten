<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TestInspector
{
    private static array $classes = [];

    protected array $ignore;

    public function __construct(array $config)
    {
        $this->ignore = $config['ignore'];
    }

    public function getCurrentTestInfo(): TestInfo
    {
        $trace = TestTrace::get();

        $testClassInfo = $this->makeTestClassInfo($trace->getClassName());

        return $this->makeTestMethodInfo($testClassInfo, $trace->getMethodName());
    }

    public function getInfo($className, $methodName): TestInfo
    {
        $testClassInfo = $this->makeTestClassInfo($className);

        return $this->makeTestMethodInfo($testClassInfo, $methodName);
    }

    private function makeTestClassInfo($name)
    {
        if (isset(static::$classes[$name])) {
            return static::$classes[$name];
        }

        $annotations = Annotations::fromClass($name);

        $options = $this->getOptionsFrom($annotations);

        return static::$classes[$name] = new TestClassInfo($name, $this->getTextsFrom($annotations), $options);
    }

    protected function makeTestMethodInfo(TestClassInfo $testClassInfo, string $methodName)
    {
        $annotations = Annotations::fromMethod($testClassInfo->getClassName(), $methodName);

        $options = array_merge($testClassInfo->getOptions(), $this->getOptionsFrom($annotations));

        if ($this->ignoreTest($testClassInfo->getClassName(), $methodName, $options)) {
            return new IgnoredTest;
        }

        return new TestMethodInfo($testClassInfo, $methodName, $this->getTextsFrom($annotations));
    }

    protected function getOptionsFrom($annotations): array
    {
        if (! $annotations->has('enlighten')) {
            return [];
        }

        $options = json_decode($annotations->get('enlighten'), JSON_OBJECT_AS_ARRAY);

        return array_merge(['include' => true], $options ?: []);
    }

    protected function getTextsFrom(Collection $annotations): array
    {
        return [
            'title' => $annotations->get('testdox'),
            'description' => $annotations->get('description'),
        ];
    }

    private function ignoreTest(string $className, string $methodName, array $options): bool
    {
        // If the test has been explicitly ignored via the
        // annotation options we need to ignore the test.
        if (Arr::get($options, 'ignore', false)) {
            return true;
        }

        // If the test has been explicitly included via the
        // annotation options we need to include the test.
        if (Arr::get($options, 'include', false)) {
            return false;
        }

        // Otherwise check the patterns to ignore we've got from the
        // config to determine if the test should still be ignored.
        return Str::is($this->ignore, $className) || Str::is($this->ignore, $methodName);
    }
}
