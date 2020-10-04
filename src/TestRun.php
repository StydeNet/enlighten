<?php

namespace Styde\Enlighten;

class TestRun
{
    private static $instance;

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    protected $classes = [];

    private function __construct()
    {
    }

    public function get($className)
    {
        return $this->classes[$className];
    }

    public function has($className): bool
    {
        return isset ($this->classes[$className]);
    }

    public function add($className, TestInfo $testInfo): TestInfo
    {
        $this->classes[$className] = $testInfo;

        return $testInfo;
    }
}
