<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Styde\Enlighten\Utils\Annotations;
use Styde\Enlighten\Utils\TestTrace;

class TestInspector
{
    private static ?TestClassInfo $currentTestClass = null;
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

        $info = $this->getInfo($trace['class'], $trace['function']);

        if ($info->isIgnored()) {
            return $info;
        }

        return $info->addLine($trace['start_line']);
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

        $annotations = $this->annotations->getFromMethod($className, $methodName);

        if ($this->ignoreTest($className, $methodName, $annotations->get('enlighten', []))) {
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
        $annotations = $this->annotations->getFromClass($name);

        $this->classOptions = $annotations->get('enlighten', []);

        return new TestClassInfo($this->testRun, $name, $this->getTextsFrom($annotations));
    }

    protected function getTextsFrom(Collection $annotations): array
    {
        return [
            'title' => $annotations->get('title') ?: $annotations->get('testdox'),
            'description' => $annotations->get('description'),
        ];
    }

    private function ignoreTest(string $className, string $methodName, array $options): bool
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
