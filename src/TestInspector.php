<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TestInspector
{
    private static ?TestClassInfo $currentTestClass = null;
    private static ?TestInfo $currentTestMethod = null;

    private TestRun $testRun;

    protected array $ignore;

    public function __construct(TestRun $testRun, array $config)
    {
        $this->testRun = $testRun;
        $this->ignore = $config['ignore'];
    }

    public function getCurrentTestInfo(): TestInfo
    {
        $trace = TestTrace::get();

        $info = $this->getInfo($trace->className, $trace->methodName);

        if ($info->isIgnored()) {
            return $info;
        }

        return $info->addLine($trace->line);
    }

    public function getInfo($className, $methodName): TestInfo
    {
        if (optional(static::$currentTestMethod)->is($className, $methodName)) {
            return static::$currentTestMethod;
        }

        return static::$currentTestMethod = $this->makeTestMethodInfo($className, $methodName);
    }

    protected function makeTestMethodInfo(string $className, string $methodName): TestInfo
    {
        $testClassInfo = $this->getClassInfo($className);

        $annotations = Annotations::fromMethod($className, $methodName);

        $options = array_merge($testClassInfo->getOptions(), $this->getOptionsFrom($annotations));

        if ($this->ignoreTest($className, $methodName, $options)) {
            return new IgnoredTest($className, $methodName);
        }

        return new TestMethodInfo($testClassInfo, $methodName, $this->getTextsFrom($annotations));
    }

    private function getClassInfo($className): TestClassInfo
    {
        if (optional(static::$currentTestClass)->is($className)) {
            return static::$currentTestClass;
        }

        return static::$currentTestClass = $this->makeTestClassInfo($className);
    }

    private function makeTestClassInfo($name): TestClassInfo
    {
        $annotations = Annotations::fromClass($name);

        return new TestClassInfo(
            $this->testRun, $name, $this->getTextsFrom($annotations), $this->getOptionsFrom($annotations)
        );
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
