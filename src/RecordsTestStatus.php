<?php

namespace Styde\Enlighten;

use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\TestRunner;

trait RecordsTestStatus
{
    public function recordTestStatus()
    {
        $this->beforeApplicationDestroyed(function () {
            $this->saveTestStatus(
                get_class($this), $this->getName(), $this->getStatusAsText()
            );
        });
    }

    private function saveTestStatus(string $className, string $methodName, string $statusText)
    {
        DB::connection('enlighten')
            ->table('enlighten_examples')
            ->join('enlighten_example_groups', 'enlighten_examples.group_id', '=', 'enlighten_example_groups.id')
            ->where('enlighten_example_groups.class_name', $className)
            ->where('enlighten_examples.method_name', $methodName)
            ->update(['test_status' => $statusText]);
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
