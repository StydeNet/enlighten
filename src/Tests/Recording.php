<?php

namespace Styde\Enlighten\Tests;

class Recording
{
    protected static bool $enabled = false;

    public static function enable()
    {
        static::$enabled = true;
    }

    public static function disable()
    {
        static::$enabled = false;
    }

    public static function isEnabled()
    {
        return static::$enabled;
    }
}
