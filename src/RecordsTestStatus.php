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
        // Create or update the example.
        $group = ExampleGroup::firstOrCreate([
            'class_name' => $className,
        ], [
            'title' => $className,
        ]);

        $example = Example::where([
            'group_id' => $group->id,
            'method_name' => $methodName,
        ])->firstOrNew();

        if ($example->exists) {
            $example->update(['test_status' => $statusText]);
        } else {
            $example->fill([
                'title' => $methodName,
                'test_status' => $statusText,
            ])->save();
        }
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
