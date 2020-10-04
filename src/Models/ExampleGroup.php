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
}
