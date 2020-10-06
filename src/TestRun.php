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

    public static function destroy()
    {
        self::$instance = null;
    }

    private $run;

    private function __construct()
    {
        $this->run = Run::firstOrNew([
            'branch' => GitInfo::currentBranch(),
            'head' => GitInfo::head(),
            'modified' => GitInfo::modified(),
        ]);
    }

    public function save()
    {
        $this->run->save();

        return $this->run;
    }
}
