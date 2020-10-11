<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleQuery extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_queries';

    protected $guarded = [];

    protected $casts = [
        'bindings' => 'array',
    ];
}
