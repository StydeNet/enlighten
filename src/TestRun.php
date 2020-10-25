<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Facades\GitInfo;
use Styde\Enlighten\Facades\VersionControl;
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

    public function getRun()
    {
        if ($this->run) {
            return $this->run;
        }

        return $this->run = Run::firstOrNew([
            'branch' => VersionControl::currentBranch(),
            'head' => VersionControl::head(),
            'modified' => VersionControl::modified(),
        ]);
    }

    public function save(): Run
    {
        $run = $this->getRun();

        $run->save();

        return $run;
    }

    public function reset()
    {
        if ($this->hasBeenReset) {
            return;
        }

        $this->getRun()->groups()->delete();

        $this->hasBeenReset = true;
    }

    public function saveFailedTestLink(TestExample $testExample)
    {
        $this->failedTestLinks[$testExample->getSignature()] = $testExample->getLink();
    }

    public function getFailedTestLink(string $signature): ?string
    {
        return $this->failedTestLinks[$signature] ?? null;
    }
}
