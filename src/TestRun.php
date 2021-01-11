<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Contracts\Example;
use Styde\Enlighten\Models\Run;

class TestRun
{
    /**
     * @var static|null
     */
    private static $instance = null;

    /**
     * @var Run|null
     */
    private $run = null;

    /**
     * @var bool
     */
    private $hasBeenReset = false;

    /**
     * @var bool
     */
    private $missingSetup = false;

    /**
     * @var array
     */
    private $failedTestLinks = [];

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new self;
        }

        return static::$instance;
    }

    public static function resetInstance()
    {
        static::$instance = null;
    }

    private function __construct()
    {
        // Use Singleton Pattern
    }

    public function reportMissingSetup()
    {
        $this->missingSetup = true;
    }

    public function missingSetup()
    {
        return $this->missingSetup;
    }

    public function saveFailedTestLink(Example $example)
    {
        $this->failedTestLinks[$example->getSignature()] = $example->getUrl();
    }

    public function getFailedTestLink(string $signature): ?string
    {
        return $this->failedTestLinks[$signature] ?? null;
    }
}
