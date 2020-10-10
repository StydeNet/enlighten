<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleException extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_exceptions';

    protected $guarded = [];

    protected $casts = [
        'code' => 'int',
        'trace' => 'array',
    ];
}
