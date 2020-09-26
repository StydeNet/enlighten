<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class TestInspector
{
    private static array $classes = [];

    protected array $exclude;
    protected ExcludedTest $excludedTest;

    public function __construct(array $config)
    {
        $this->exclude = $config['exclude'];
        $this->excludedTest = new ExcludedTest;
    }

    public function getInfo(): TestInfo
    {
        $trace = TestTrace::get();

        if ($trace->isExcluded($this->exclude))  {
            return $this->excludedTest;
        }

        $testClassInfo = $this->makeTestClassInfo($trace->getClassName());

        if ($testClassInfo->isExcluded()) {
            return $this->excludedTest;
        }

        return $this->makeTestMethodInfo($testClassInfo, $trace->getMethodName());
    }

    private function makeTestClassInfo($name)
    {
        if (isset(static::$classes[$name])) {
            return static::$classes[$name];
        }

        $annotations = Annotations::fromClass($name);

        $config = $this->getConfigFrom($annotations);

        if ($this->isExcludedFromConfig($config)) {
            return $this->excludedTest;
        }

        return static::$classes[$name] = new TestClassInfo($name, $this->getTextsFrom($annotations));
    }

    protected function makeTestMethodInfo(TestClassInfo $testClassInfo, string $methodName)
    {
        $annotations = Annotations::fromMethod($testClassInfo->getClassName(), $methodName);

        $config = $this->getConfigFrom($annotations);

        if ($this->isExcludedFromConfig($config)) {
            return $this->excludedTest;
        }

        return new TestMethodInfo($testClassInfo, $methodName, $this->getTextsFrom($annotations));
    }

    protected function isExcludedFromConfig(array $config): bool
    {
        return Arr::get($config, 'exclude', false);
    }

    protected function getConfigFrom($annotations): array
    {
        return json_decode($annotations->get('enlighten', '{}'), JSON_OBJECT_AS_ARRAY);
    }

    protected function getTextsFrom(Collection $annotations): array
    {
        return [
            'title' => $annotations->get('testdox'),
            'description' => $annotations->get('description'),
        ];
    }
}
