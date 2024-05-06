<?php

namespace Styde\Enlighten\Utils;

use Throwable;

class JsonFormatter
{
    public static function prettify(array $input): string
    {
        try {
            return json_encode($input, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Throwable) {
            return '';
        }
    }
}
