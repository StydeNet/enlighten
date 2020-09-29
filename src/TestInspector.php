<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TestInspector
{
    private static array $classes = [];

    protected array $exclude;

    public function __construct(array $config)
    {
        $this->exclude = $config['exclude'];
    }

    public function getInfo(): TestInfo
    {
        $trace = TestTrace::get();

        $testClassInfo = $this->makeTestClassInfo($trace->getClassName());

        return $this->makeTestMethodInfo($testClassInfo, $trace->getMethodName());
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

        if ($this->excludeTest($testClassInfo->getClassName(), $methodName, $options)) {
            return new ExcludedTest;
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

    private function excludeTest(string $className, string $methodName, array $options): bool
    {
        // If the test has been explicitly excluded via the
        // annotation options we need to exclude the test.
        if (Arr::get($options, 'exclude', false)) {
            return true;
        }

        // If the test has been explicitly included via the
        // annotation options we need to include the test.
        if (Arr::get($options, 'include', false)) {
            return false;
        }

        // Otherwise check the excluded patterns we've got from the
        // config to determine if the test should be excluded.
        return Str::is($this->exclude, $className) || Str::is($this->exclude, $methodName);
    }
}
