<?php

use Styde\Enlighten\CodeExampleCreator;
use Styde\Enlighten\Exceptions\LaravelNotPresent;

if (! function_exists('enlighten')) {
    function enlighten(Closure $callback, ...$params)
    {
        if (! app() instanceof \Illuminate\Foundation\Application) {
            throw new LaravelNotPresent;
        }

        return app(CodeExampleCreator::class)->createSnippet($callback, $params);
    }
}
