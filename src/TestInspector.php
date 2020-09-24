<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Static_;
use ReflectionClass;
use ReflectionMethod;

class TestInspector
{
    private static $testClasses = [];

    public function getInfo()
    {
        $trace = $this->getTestTrace();

        return [
            $testClassInfo = $this->makeTestClassInfo($trace['class']),
            $this->makeTestMethodInfo($trace, $testClassInfo)
        ];
    }

    protected function getTestTrace(): array
    {
        return collect(debug_backtrace())->first(function ($trace) {
            return Str::contains($trace['file'], '/phpunit/')
                && Str::endsWith($trace['file'], '/Framework/TestCase.php');
        });
    }

    protected function getConfigFrom($docBlock): array
    {
        $classConfig = $this->getAnnotation($docBlock, 'enlighten');

        if (is_null($classConfig)) {
            return [];
        }

        return json_decode($classConfig, JSON_OBJECT_AS_ARRAY);
    }

    protected function getAnnotation($docblock, $annotation): ?string
    {
        preg_match_all("#@{$annotation} (.*?)\n#s", $docblock, $annotations);

        if (empty ($annotations[1])) {
            return null;
        }

        return trim($annotations[1][0], '. ');
    }

    private function makeTestClassInfo($class)
    {
        if (isset(static::$testClasses[$class])) {
            return static::$testClasses[$class];
        }

        $classDocBlock = (new ReflectionClass($class))->getDocComment();

        return static::$testClasses[$class] = new TestClassInfo(
            $class, $this->getConfigFrom($classDocBlock), [
                'title' => $this->getAnnotation($classDocBlock, 'testdox'),
                'description' => $this->getAnnotation($classDocBlock, 'description'),
            ]
        );
    }

    protected function makeTestMethodInfo(array $trace, TestClassInfo $testClassInfo): TestInfo
    {
        $methodDocBlock = (new ReflectionMethod($trace['class'], $trace['function']))->getDocComment();

        return new TestInfo(
            $trace,
            array_merge(
                $testClassInfo->getConfig(),
                $this->getConfigFrom($methodDocBlock)
            ),
            [
                'method_title' => $this->getAnnotation($methodDocBlock, 'testdox'),
                'method_description' => $this->getAnnotation($methodDocBlock, 'description'),
            ]
        );
    }
}
