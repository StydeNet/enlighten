<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Utils\GitInfo;

class TestRun
{
    private static bool $hasBeenReset = false;

    private GitInfo $gitInfo;

    private ?Run $run = null;

    private string $context = 'test';

    private static $failedTestLinks = [];

    public static function saveFailedTestLink(TestMethodInfo $testMethodInfo)
    {
        static::$failedTestLinks[$testMethodInfo->getSignature()] = $testMethodInfo->getLink();
    }

    public static function getFailedTestLink(string $signature): string
    {
        return static::$failedTestLinks[$signature];
    }

    public function __construct(GitInfo $gitInfo)
    {
        $this->gitInfo = $gitInfo;

        $this->run = Run::firstOrNew([
            'branch' => $this->gitInfo->currentBranch(),
            'head' => $this->gitInfo->head(),
            'modified' => $this->gitInfo->modified(),
        ]);
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function save(): Run
    {
        $this->run->save();

        return $this->run;
    }

    public function reset()
    {
        if (static::$hasBeenReset) {
            return;
        }

        $this->run->delete();

        static::$hasBeenReset = true;
    }
}
