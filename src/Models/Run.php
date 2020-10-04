<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_runs';

    protected $guarded = [];

    public function groups()
    {
        return $this->hasMany(ExampleGroup::class);
    }
}
