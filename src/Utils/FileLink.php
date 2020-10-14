<?php

namespace Styde\Enlighten\Utils;

use Illuminate\Support\Arr;

class FileLink
{
    public static $editors = [
        'phpstorm' => 'phpstorm://open?file={path}&line={line}',
        'sublime' => 'subl://open?url=file://{path}&line={line}',
        'vscode' => 'vscode://file/{path}:{line}',
    ];

    public static $template;

    public static function get(string $path, ?int $line = 1)
    {
        if (static::$template == null) {
            static::$template = Arr::get(
                static::$editors, config('enlighten.editor', 'phpstorm'),
                Arr::first(static::$editors)
            );
        }

        return str_replace(['{path}', '{line}'], [urlencode(base_path($path)), $line], static::$template);
    }
}
