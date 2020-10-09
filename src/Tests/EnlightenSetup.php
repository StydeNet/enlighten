<?php

namespace Styde\Enlighten\Tests;

use PHPUnit\TextUI\TestRunner;
use Styde\Enlighten\TestInspector;
use Styde\Enlighten\TestRun;

trait EnlightenSetup
{
    private static ?TestRun $testRun = null;

    public function setUpEnlighten()
    {
        if ($this->app->make('config')->get('enlighten.enabled')) {
            $this->beforeApplicationDestroyed(fn() => $this->saveTestExample());

            $this->beforeApplicationDestroyed(fn() => $this->preserveTestRun());

            $this->afterApplicationCreated(fn() => $this->restoreTestRun());
        }
    }

    private function preserveTestRun()
    {
        static::$testRun = $this->app->make(TestRun::class);
    }

    private function restoreTestRun()
    {
        if (static::$testRun) {
            $this->app->instance(TestRun::class, static::$testRun);
        }
    }

    private function saveTestExample()
    {
        $test = $this->app->make(TestInspector::class)
            ->getInfo(get_class($this), $this->getName());

        if ($test->isIgnored()) {
            return;
        }

        $test->addStatus($this->getStatusAsText())
            ->save();
    }

    private function getStatusAsText()
    {
        $statuses = [
            TestRunner::STATUS_PASSED => 'passed',
            TestRunner::STATUS_SKIPPED => 'skipped',
            TestRunner::STATUS_INCOMPLETE => 'incomplete',
            TestRunner::STATUS_FAILURE => 'failure',
            TestRunner::STATUS_ERROR => 'error',
            TestRunner::STATUS_RISKY => 'risky',
            TestRunner::STATUS_WARNING => 'warning',
        ];

        return $statuses[$this->getStatus()] ?? 'unknown';
    }
}
