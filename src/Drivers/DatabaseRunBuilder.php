<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\Contracts\ExampleGroupBuilder;
use Styde\Enlighten\Contracts\Run as RunContract;
use Styde\Enlighten\Contracts\RunBuilder;
use Styde\Enlighten\Facades\VersionControl;
use Styde\Enlighten\Models\Run;

class DatabaseRunBuilder implements RunBuilder
{
    /**
     * @var Run
     */
    protected $run;

    public function __construct()
    {
        //...
    }

    public function newExampleGroup(): ExampleGroupBuilder
    {
        return new DatabaseExampleGroupBuilder($this);
    }

    public function reset(): void
    {
        $this->initRun();

        $this->run->groups()->delete();
    }

    public function save(): RunContract
    {
        $this->initRun();

        $this->run->save();

        return $this->run;
    }

    public function getRun(): RunContract
    {
        $this->initRun();

        return $this->run->fresh();
    }

    protected function initRun()
    {
        if ($this->run !== null) {
            return;
        }

        $this->run = Run::firstOrNew([
            'branch' => VersionControl::currentBranch(),
            'head' => VersionControl::head(),
            'modified' => VersionControl::modified(),
        ]);
    }
}
