<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Utils\GitInfo;

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
        if ($this->run != null) {
            return $this->run;
        }

        $this->run = Run::firstOrCreate([
            'branch' => $this->gitInfo->currentBranch(),
            'head' => $this->gitInfo->head(),
            'modified' => $this->gitInfo->modified(),
        ]);

        return $this->run;
    }
}
