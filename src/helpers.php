<?php

use Styde\Enlighten\CodeExampleCreator;

if (! function_exists('enlighten')) {
    function enlighten(Closure $callback, ...$params)
    {
        return app(CodeExampleCreator::class)->createSnippet($callback, $params);
    }
}
