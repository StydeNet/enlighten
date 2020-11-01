<?php


use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Utils\JsonFormatter;

if (! function_exists('enlighten')) {
    function enlighten(Closure $callback)
    {
        return Enlighten::test($callback);
    }
}

if (! function_exists('enlighten_json_prettify')) {
    function enlighten_json_prettify(array $input)
    {
        return JsonFormatter::prettify($input);
    }
}
