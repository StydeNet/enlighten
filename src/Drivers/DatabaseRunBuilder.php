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
        $this->run = Run::firstOrNew([
            'branch' => VersionControl::currentBranch(),
            'head' => VersionControl::head(),
            'modified' => VersionControl::modified(),
        ]);
    }

    public function newExampleGroup(): ExampleGroupBuilder
    {
        return new DatabaseExampleGroupBuilder($this);
    }

    public function reset(): void
    {
        $this->run->groups()->delete();
    }

    public function save(): RunContract
    {
        $this->run->save();

        return $this->run;
    }

    public function getRun(): RunContract
    {
        return $this->run->fresh();
    }
}
