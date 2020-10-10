<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Utils\GitInfo;

class TestRun
{
    private GitInfo $gitInfo;

    private ?Run $run = null;

    static bool $hasBeenReset = false;

    public function __construct(GitInfo $gitInfo)
    {
        $this->gitInfo = $gitInfo;

        $this->run = Run::firstOrNew([
            'branch' => $this->gitInfo->currentBranch(),
            'head' => $this->gitInfo->head(),
            'modified' => $this->gitInfo->modified(),
        ]);
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
