<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Styde\Enlighten\Utils\Annotations;

class TestInspector
{
    /**
     * @var TestExampleGroup|null
     */
    private static $currentTestClass = null;

    /**
     * @var TestInfo|null
     */
    private $currentTestExample = null;

    /**
     * @var array
     */
    protected $classOptions = [];

    /**
     * @var TestRun
     */
    private $testRun;

    /**
     * @var Annotations
     */
    private $annotations;

    /**
     * @var array
     */
    protected $ignore;

    public function __construct(TestRun $testRun, Annotations $annotations, array $config)
    {
        $this->testRun = $testRun;
        $this->annotations = $annotations;
        $this->ignore = $config['ignore'];
    }

    public function createTestExample($className, $methodName): TestInfo
    {
        return $this->currentTestExample = $this->makeTestExample($className, $methodName);
    }

    public function getCurrentTestExample()
    {
        return $this->currentTestExample;
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
