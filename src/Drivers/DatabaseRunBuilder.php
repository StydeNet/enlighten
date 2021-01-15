<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\Contracts\ExampleGroupBuilder;
use Styde\Enlighten\Contracts\RunBuilder;
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
    protected static $hasBeenReset = false;

    public function newExampleGroup(): ExampleGroupBuilder
    {
        return new DatabaseExampleGroupBuilder($this);
    }

    private function getRun()
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

    public function reset(): void
    {
        if (static::$hasBeenReset) {
            return;
        }

        $this->getRun()->groups()->delete();

        static::$hasBeenReset = true;
    }

    public function save()
    {
        $this->getRun()->save();

        return $this->run;
    }
}
