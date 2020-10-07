<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;

class TestRun
{
    private GitInfo $gitInfo;

    private ?Run $run = null;

    public function __construct(GitInfo $gitInfo)
    {
        $this->gitInfo = $gitInfo;
    }

    public function save(): Run
    {
        if ($this->run == null) {
            $this->run = Run::firstOrNew([
                'branch' => $this->gitInfo->currentBranch(),
                'head' => $this->gitInfo->head(),
                'modified' => $this->gitInfo->modified(),
            ]);
        }

        $this->run->save();

        return $this->run;
    }
}
