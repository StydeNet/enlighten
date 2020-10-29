<?php

namespace Styde\Enlighten\Models\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Styde\Enlighten\Models\Status;

/** @property-read Collection $stats */
trait GetStats
{
    abstract public function stats();

    public function getPassingTestsCount(): int
    {
        return $this->stats
            ->filter(function ($stat) {
                return $stat->getStatus() === Status::SUCCESS;
            })
            ->sum('count');
    }

    public function getTestsCount(): int
    {
        return $this->stats->sum('count');
    }

    // Statusable
    public function getStatus(): string
    {
        if ($this->getPassingTestsCount() === $this->getTestsCount()) {
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
