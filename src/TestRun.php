<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;

class TestRun
{
    private static ?self $instance = null;

    private Run $run;

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public static function destroy(): void
    {
        self::$instance = null;
    }

    private function __construct()
    {
        $this->run = Run::firstOrNew([
            'branch' => GitInfo::currentBranch(),
            'head' => GitInfo::head(),
            'modified' => GitInfo::modified(),
        ]);
    }

    public function save(): Run
    {
        $this->run->save();

        return $this->run;
    }
}
