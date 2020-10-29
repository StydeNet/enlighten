<?php

namespace Styde\Enlighten\Models\Concerns;

use Styde\Enlighten\Models\Status;

trait GetStats
{
    abstract public function stats();

    public function getPassingTestsCount(): int
    {
        return $this->stats
            ->filter(function ($stat) {
                return $stat->getStatus() === Status::SUCCESS;
            })
            ->sum('count', 0);
    }

    public function getTestsCount(): int
    {
        return $this->stats->sum('count');
    }

    // Statusable
    public function getStatus(): string
    {
        if ($this->passing_tests_count === $this->tests_count) {
            return Status::SUCCESS;
        }

        if ($this->stats->first(function ($stat) {
            return $stat->getStatus() === Status::FAILURE;
        })) {
            return Status::FAILURE;
        }

        return Status::WARNING;
    }
}
