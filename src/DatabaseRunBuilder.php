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

    public function newExampleGroup(): ExampleGroupBuilder
    {
        return (new DatabaseExampleGroupBuilder)
            ->setRunBuilder($this);
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
