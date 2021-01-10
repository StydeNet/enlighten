<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Facades\VersionControl;
use Styde\Enlighten\Models\Run;

class DatabaseRunBuilder implements RunBuilder
{
    /**
     * @var Run
     */
    protected $run;

    /**
     * @var bool
     */
    protected $hasBeenReset = false;

    /**
     * @var TestRun
     */
    private $testRun;

    public function __construct(TestRun $testRun)
    {
        $this->testRun = $testRun;
    }

    public function newExampleGroup(): ExampleGroupBuilder
    {
        return (new DatabaseExampleGroupBuilder)
            ->setRunBuilder($this)
            ->setTestRun($this->testRun);
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

    public function reset()
    {
        if ($this->hasBeenReset) {
            return;
        }

        $this->getRun()->groups()->delete();

        $this->hasBeenReset = true;
    }

    public function save()
    {
        $this->getRun()->save();

        return $this->run;
    }
}
