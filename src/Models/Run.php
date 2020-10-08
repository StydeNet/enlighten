<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Models\Concerns\GetsStatsFromGroups;

class Run extends Model implements Statable
{
    use GetsStatsFromGroups;

    protected $connection = 'enlighten';

    protected $table = 'enlighten_runs';

    protected $guarded = [];

    public function groups()
    {
        return $this->hasMany(ExampleGroup::class);
    }
}
