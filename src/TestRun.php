<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;

class TestRun
{
    private GitInfo $gitInfo;

    private Run $run;

    public function __construct(GitInfo $gitInfo)
    {
        dump('TestRun::__construct');

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
}
