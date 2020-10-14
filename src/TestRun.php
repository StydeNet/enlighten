<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Facades\GitInfo;

class TestRun
{
    private static ?self $instance = null;

    private Run $run;

    private bool $hasBeenReset = false;

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
        $this->run = Run::firstOrNew([
            'branch' => GitInfo::currentBranch(),
            'head' => GitInfo::head(),
            'modified' => GitInfo::modified(),
        ]);
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

    public function saveFailedTestLink(TestExample $testExample)
    {
        $this->failedTestLinks[$testExample->getSignature()] = $testExample->getLink();
    }

    public function getFailedTestLink(string $signature): string
    {
        return $this->failedTestLinks[$signature];
    }
}
