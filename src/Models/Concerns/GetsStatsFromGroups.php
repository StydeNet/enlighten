<?php

namespace Styde\Enlighten\Models\Concerns;

use Styde\Enlighten\Models\Status;

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
            return Status::SUCCESS;
        }

        if ($this->groups->firstWhere('status', Status::FAILURE)) {
            return Status::FAILURE;
        }

        return Status::WARNING;
    }
}
