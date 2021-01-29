<?php


use Styde\Enlighten\Enlighten;
use Styde\Enlighten\Utils\JsonFormatter;

if (! function_exists('enlighten')) {
    function enlighten($keyOrCallback, $callback = null)
    {
        return Enlighten::test($keyOrCallback, $callback);
    }
}

if (! function_exists('enlighten_json_prettify')) {
    function enlighten_json_prettify(array $input)
    {
        return JsonFormatter::prettify($input);
    }
}
