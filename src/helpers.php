<?php


use Styde\Enlighten\Facades\Enlighten;

if (! function_exists('enlighten')) {
    function enlighten(Closure $callback)
    {
        return Enlighten::test($callback);
    }
}
