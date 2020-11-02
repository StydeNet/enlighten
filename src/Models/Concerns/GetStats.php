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
            ->where('status', Status::SUCCESS)
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

        if ($this->stats->firstWhere('status', Status::FAILURE)) {
            return Status::FAILURE;
        }

        return Status::WARNING;
    }
}
