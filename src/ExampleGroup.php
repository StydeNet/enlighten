<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Model;

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
}
