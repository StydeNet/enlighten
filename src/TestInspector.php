<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class TestInspector
{
    public function getInfo()
    {
        $trace = $this->getTestTrace();

        $classDocBlock = (new ReflectionClass($trace['class']))->getDocComment();

        $methodDocBlock = (new ReflectionMethod($trace['class'], $trace['function']))->getDocComment();

        return new TestInfo(
            $trace,
            array_merge(
                $this->getConfigFrom($classDocBlock),
                $this->getConfigFrom($methodDocBlock)
            ),
            [
                'method_title' => $this->getAnnotation($methodDocBlock, 'testdox'),
                'method_description' => $this->getAnnotation($methodDocBlock, 'description'),
            ]
        );
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
}
