<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleSnippet extends Model
{
    const CLASS_NAME = '--class_name';
    const ATTRIBUTES = '--attributes';

    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_snippets';

    protected $guarded = [];

    protected $casts = [
        // Transform result into JSON with the intention of preserving
        // the original data type.This is not going to work for all
        // cases, so we will need to implement another solution.
        'result' => 'array',
        'params' => 'array',
    ];
}
