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

    public function http_data()
    {
        return $this->belongsTo(HttpData::class);
    }

    // Accessors

    public function getContextAttribute($value)
    {
        return is_null($this->http_data_id) ? 'test' : 'request';
    }
}
