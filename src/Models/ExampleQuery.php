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
        'request_id' => 'int',
        'snippet_id' => 'int',
    ];

    public function request()
    {
        return $this->belongsTo(ExampleRequest::class);
    }

    public function snippet()
    {
        return $this->belongsTo(ExampleSnippet::class);
    }

    // Accessors

    public function getContextAttribute()
    {
        if ($this->request_id !== null) {
            return 'request';
        }

        if ($this->snippet_id !== null) {
            return 'snippet';
        }

        return 'test';
    }
}
