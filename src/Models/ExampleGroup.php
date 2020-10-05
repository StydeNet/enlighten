<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Styde\Enlighten\Module;
use Styde\Enlighten\TestSuite;

class ExampleGroup extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_groups';

    protected $guarded = [];

    // Query methods
    public  static function findByTestSuite(TestSuite $suite) : Collection
    {
        if (empty($suite)) {
            return Collection::make();
        }

        return static::bySuite($suite)->get();
    }

    // Relationships
    public function examples()
    {
        return $this->hasMany(Example::class, 'group_id')->orderBy('id');
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
    public function scopeBySuite($query, TestSuite $suite) : Builder
    {
        return $query->where('class_name', 'like', "Tests%{$suite->key}%");
    }

    // Accessors
    public function getPassingTestsCountAttribute()
    {
        return data_get($this->stats->firstWhere('test_status', 'passed'), 'count', 0);
    }

    public function getTestsCountAttribute()
    {
        return $this->stats->sum('count');
    }

    public function getStatusAttribute() : string
    {
        if ($this->passing_tests_count === $this->tests_count) {
            return 'passed';
        }

        if ($this->stats->whereIn('test_status', ['failure', 'error'])->isNotEmpty()) {
            return 'failed';
        }

        return 'warned';
    }

    public function getPassedAttribute()
    {
        return $this->status === 'passed';
    }

    public function getFailedAttribute()
    {
        return $this->status === 'failed';
    }
}
