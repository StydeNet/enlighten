<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Models\Concerns\GetStats;

class Run extends Model implements Statable
{
    use GetStats;

    protected $connection = 'enlighten';

    protected $table = 'enlighten_runs';

    protected $guarded = [];

    public function groups()
    {
        return $this->hasMany(ExampleGroup::class);
    }

    public function stats()
    {
        return $this->hasManyThrough(Example::class, ExampleGroup::class, 'run_id', 'group_id')
            ->selectRaw('
                DISTINCT(test_status),
                COUNT(enlighten_examples.id) as count
            ')
            ->groupBy('test_status', 'run_id');
    }

    // Accessors

    public function getSignatureAttribute($value)
    {
        if ($this->modified) {
            return "{$this->branch} * {$this->head}";
        }

        return "{$this->branch} {$this->head}";
    }
}
