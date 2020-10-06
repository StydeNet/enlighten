<?php

namespace Styde\Enlighten;

use PHPUnit\TextUI\TestRunner;

trait RecordsTestStatus
{
    public function recordTestStatus()
    {
        if ($this->app->make('config')->get('enlighten.enabled')) {
            $this->beforeApplicationDestroyed(fn() => $this->saveTestExample());
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
