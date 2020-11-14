<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Styde\Enlighten\Models\Concerns\GetStats;

class ExampleGroup extends Model implements Statable, Wrappable
{
    use GetStats;

    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_groups';

    protected $guarded = [];

    protected $casts = [
        'order_num' => 'int',
    ];

    // Relationships
    public function run()
    {
        return $this->belongsTo(Run::class);
    }

    public function examples()
    {
        return $this->hasMany(Example::class, 'group_id')
            ->orderBy('order_num')
            ->orderBy('id');
    }

    public function stats()
    {
        return $this->hasMany(Example::class, 'group_id', 'id')
            ->selectRaw('DISTINCT(status), COUNT(id) as count, group_id')
            ->groupBy('status', 'group_id');
    }

    // Helpers
    public function matches(Module $module): bool
    {
        return Str::is($module->classes, $this->class_name);
    }

    // Scopes
    public function scopeFilterByArea($query, Area $area) : Builder
    {
        return $query->where('area', $area->slug);
    }

    // Accessors

    public function getAreaTitleAttribute()
    {
        return config('enlighten.areas.'.$this->area) ?: ucwords($this->area);
    }

    public function getPassingTestsCountAttribute()
    {
        return $this->getPassingTestsCount();
    }

    public function getTestsCountAttribute()
    {
        return $this->getTestsCount();
    }

    public function getStatusAttribute(): string
    {
        return $this->getStatus();
    }

    public function getUrlAttribute()
    {
        return route('enlighten.group.show', [
            'run' => $this->run_id,
            'group' => $this->slug,
        ]);
    }

    public function getOrderAttribute()
    {
        return [$this->order_num, $this->id];
    }
}
