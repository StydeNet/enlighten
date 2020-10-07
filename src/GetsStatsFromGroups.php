<?php

namespace Styde\Enlighten;

trait GetsStatsFromGroups
{
    public function getPassingTestsCount(): int
    {
        return $this->groups->pluck('passing_tests_count')->sum();
    }

    public function getTestsCount(): int
    {
        return $this->groups->pluck('tests_count')->sum();
    }

    public function getStatus(): string
    {
        if ($this->getPassingTestsCount() === $this->getTestsCount()) {
            return 'passed';
        }

        if ($this->groups->whereIn('status', ['failed', 'error'])->isNotEmpty()) {
            return 'failed';
        }

        return 'warned';
    }
}