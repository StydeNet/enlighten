<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Styde\Enlighten\Contracts\Run as RunContract;
use Styde\Enlighten\Models\Concerns\GetStats;

class Run extends Model implements RunContract, Statable
{
    use GetStats;

    protected $connection = 'enlighten';

    protected $table = 'enlighten_runs';

    protected $guarded = [];

    // Relationships

    public function groups()
    {
        return $this->hasMany(ExampleGroup::class);
    }

    public function examples()
    {
        return $this->hasManyThrough(Example::class, ExampleGroup::class, 'run_id', 'group_id');
    }

    public function findGroup(string $slug)
    {
        return $this->groups()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function stats()
    {
        return $this->hasManyThrough(Example::class, ExampleGroup::class, 'run_id', 'group_id')
            ->selectRaw('
                DISTINCT(status),
                COUNT(enlighten_examples.id) as count
            ')
            ->groupBy('status', 'run_id');
    }

    // Run Contract

    public function isEmpty(): bool
    {
        return $this->groups()->count() == 0;
    }

    public function getFailedExamples(): SupportCollection
    {
        return $this->examples()->where('status', '!=', Status::SUCCESS)->get();
    }

    public function url(): string
    {
        return $this->getUrlAttribute();
    }

    // Accessors

    public function getAreasAttribute()
    {
        $areas = $this->groups->pluck('area')->unique();

        return Area::get($areas);
    }

    public function getSignatureAttribute($value)
    {
        if ($this->modified) {
            return "{$this->branch} * {$this->head}";
        }

        return "{$this->branch} {$this->head}";
    }

    public function getUrlAttribute()
    {
        return route('enlighten.area.show', $this);
    }

    public function getBaseUrlAttribute()
    {
        return url("enlighten/run/{$this->id}");
    }

    public function areaUrl(string $area)
    {
        return route('enlighten.area.show', [$this, $area]);
    }
}
