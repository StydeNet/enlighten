<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Styde\Enlighten\Utils\Annotations;
use Styde\Enlighten\Utils\TestTrace;

class TestInspector
{
    private static ?TestExampleGroup $currentTestClass = null;
    private static ?TestInfo $currentTestMethod = null;
    protected array $classOptions = [];

    private TestRun $testRun;
    private TestTrace $testTrace;
    private Annotations $annotations;

    protected array $ignore;

    public function __construct(TestRun $testRun, TestTrace $testTrace, Annotations $annotations, array $config)
    {
        $this->testRun = $testRun;
        $this->testTrace = $testTrace;
        $this->annotations = $annotations;
        $this->ignore = $config['ignore'];
    }

    public function getCurrentTestInfo(): TestInfo
    {
        $trace = $this->testTrace->get();

        return $this->getTestExample($trace['class'], $trace['function']);
    }

    public function getTestExample($className, $methodName): TestInfo
    {
        if (optional(static::$currentTestMethod)->is($className, $methodName)) {
            return static::$currentTestMethod;
        }

        return static::$currentTestMethod = $this->makeTestExample($className, $methodName);
    }

    protected function makeTestExample(string $className, string $methodName): TestInfo
    {
        $testClassInfo = $this->getTestExampleGroup($className);

        $annotations = $this->annotations->getFromMethod($className, $methodName);

        if ($this->ignoreTestExample($className, $methodName, $annotations->get('enlighten', []))) {
            return new IgnoredTest($className, $methodName);
        }

        return new TestExample($testClassInfo, $methodName, $this->getTextsFrom($annotations));
    }

    private function getTestExampleGroup($className): TestExampleGroup
    {
        if (optional(static::$currentTestClass)->is($className)) {
            return static::$currentTestClass;
        }

        return static::$currentTestClass = $this->makeTestExampleGroup($className);
    }

    private function makeTestExampleGroup($name): TestExampleGroup
    {
        $annotations = $this->annotations->getFromClass($name);

        $this->classOptions = $annotations->get('enlighten', []);

        return new TestExampleGroup($name, $this->getTextsFrom($annotations));
    }

    protected function getTextsFrom(Collection $annotations): array
    {
        return [
            'title' => $annotations->get('title') ?: $annotations->get('testdox'),
            'description' => $annotations->get('description'),
        ];
    }

    private function ignoreTestExample(string $className, string $methodName, array $options): bool
    {
        $options = array_merge($this->classOptions, $options);

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
