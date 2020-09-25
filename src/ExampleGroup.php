<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExampleGroup extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_groups';

    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new ExampleGroupCollection($models);
    }

    public function examples()
    {
        return $this->hasMany(Example::class, 'group_id');
    }

    public function matches(Module $module)
    {
        return Str::is($module->pattern, $this->class_name);
    }
}
