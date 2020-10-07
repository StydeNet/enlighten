<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\GetsStatsFromGroups;
use Styde\Enlighten\Statable;
use Styde\Enlighten\Statusable;

class Run extends Model implements Statable, Statusable
{
    use GetsStatsFromGroups;

    protected $connection = 'enlighten';

    protected $table = 'enlighten_runs';

    protected $guarded = [];

    public function groups()
    {
        return $this->hasMany(ExampleGroup::class);
    }

    public function hasPassed()
    {
        return $this->passed;
    }

    public function hasFailed()
    {
        return $this->failed;
    }
}
