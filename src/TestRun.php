<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\Run;

class TestRun
{
    private static $instance;

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function save()
    {
        return Run::firstOrCreate([
            'branch' => GitInfo::currentBranch(),
            'head' => GitInfo::head(),
            'modified' => GitInfo::modified(),
        ]);
    }
}
