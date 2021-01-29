<?php

namespace Styde\Enlighten\Tests;

use Styde\Enlighten\Contracts\Example;

class TestRun
{
    /**
     * @var static|null
     */
    private static $instance = null;

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

    public static function resetInstance(): void
    {
        static::$instance = null;
    }

    private function __construct()
    {
        // Use Singleton Pattern
    }

    public function reportMissingSetup(): void
    {
        $this->missingSetup = true;
    }

    public function missingSetup(): bool
    {
        return $this->missingSetup;
    }

    public function addFailedTestLink(Example $example): void
    {
        $this->failedTestLinks[$example->getSignature()] = $example->getUrl();
    }

    public function getFailedTestLink(string $signature): ?string
    {
        return $this->failedTestLinks[$signature] ?? null;
    }
}
