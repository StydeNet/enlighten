<?php

namespace Styde\Enlighten;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TestInspector
{
    private static array $testClasses = [];

    public function getInfo(): TestMethodInfo
    {
        $trace = $this->getTestTrace();

        $testClassInfo = $this->makeTestClassInfo($trace['class']);

        return $this->makeTestMethodInfo($testClassInfo, $trace['function']);
    }

    protected function getTestTrace(): array
    {
        return collect(debug_backtrace())->first(function ($trace) {
            return Str::contains($trace['file'], '/phpunit/')
                && Str::endsWith($trace['file'], '/Framework/TestCase.php');
        });
    }

    private function makeTestClassInfo($className)
    {
        if (isset(static::$testClasses[$className])) {
            return static::$testClasses[$className];
        }

        $annotations = Annotations::fromClass($className);

        return static::$testClasses[$className] = new TestClassInfo(
            $className, $this->getConfigFrom($annotations), $this->getTextsFrom($annotations)
        );
    }

    protected function makeTestMethodInfo(TestClassInfo $testClassInfo, string $methodName): TestMethodInfo
    {
        $annotations = Annotations::fromMethod($testClassInfo->getClassName(), $methodName);

        return new TestMethodInfo(
            $testClassInfo, $methodName,
            array_merge($testClassInfo->getConfig(), $this->getConfigFrom($annotations)),
            $this->getTextsFrom($annotations)
        );
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
