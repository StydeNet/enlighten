<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class ExampleGroup extends Model implements Statusable
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_groups';

    protected $guarded = [];

    // Query methods
    public  static function findByTestSuite(Area $suite) : Collection
    {
        if (empty($suite)) {
            return Collection::make();
        }

        return static::bySuite($suite)->get();
    }

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
    public function scopeBySuite($query, Area $suite) : Builder
    {
        return $query->where('class_name', 'like', "Tests\\{$suite->key}\\%");
    }

    // Accessors
    public function getSuiteAttribute()
    {
        return Str::slug(explode('\\', $this->class_name)[1]);
    }

    public function getPassingTestsCountAttribute()
    {
        return $this->stats
            ->filter(fn($stat) => $stat->getStatus() === Status::SUCCESS)
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

        if ($this->stats->first(fn($stat) => $stat->getStatus() === Status::FAILURE)) {
            return Status::FAILURE;
        }

        return Status::WARNING;
    }

    public function getUrlAttribute()
    {
        return route('enlighten.group.show', [
            'run' => $this->run_id,
            'suite' => $this->suite,
            'group' => $this->id,
        ]);
    }
}
