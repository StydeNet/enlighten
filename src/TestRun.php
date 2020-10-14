<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Facades\GitInfo;

class TestRun
{
    private static ?self $instance = null;

    private Run $run;

    private bool $hasBeenReset = false;

    private string $context = 'test';

    private static $failedTestLinks = [];

    public static function saveFailedTestLink(TestExample $testMethodInfo)
    {
        static::$failedTestLinks[$testMethodInfo->getSignature()] = $testMethodInfo->getLink();
    }

    public static function getFailedTestLink(string $signature): string
    {
        return static::$failedTestLinks[$signature];
    }

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
        $this->run = Run::firstOrNew([
            'branch' => GitInfo::currentBranch(),
            'head' => GitInfo::head(),
            'modified' => GitInfo::modified(),
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
        if ($this->hasBeenReset) {
            return;
        }

        $this->run->delete();

        $this->hasBeenReset = true;
    }
}
