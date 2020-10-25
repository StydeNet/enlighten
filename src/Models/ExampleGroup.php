<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExampleGroup extends Model implements Statusable
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_groups';

    protected $guarded = [];

    // Relationships
    public function examples()
    {
        return $this->hasMany(Example::class, 'group_id')
            ->orderBy('id');
    }

    public function stats()
    {
        return $this->hasMany(Example::class, 'group_id', 'id')
            ->selectRaw('DISTINCT(test_status), COUNT(id) as count, group_id')
            ->groupBy('test_status', 'group_id');
    }

    // Helpers
    public function matches(Module $module)
    {
        return Str::is($module->pattern, $this->class_name);
    }

    // Scopes
    public function scopeFilterByArea($query, Area $area) : Builder
    {
        return $query->where('area', $area->slug);
    }

    // Accessors

    public function getPassingTestsCountAttribute()
    {
        return $this->stats
            ->filter(function ($stat) {
                return $stat->getStatus() === Status::SUCCESS;
            })
            ->sum('count', 0);
    }

    public function getTestsCountAttribute()
    {
        return $this->stats->sum('count');
    }

    public function getStatusAttribute(): string
    {
        return $this->getStatus();
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

    public function getUrlAttribute()
    {
        return route('enlighten.group.show', [
            'run' => $this->run_id,
            'area' => $this->area,
            'group' => $this->slug,
        ]);
    }
}
