<?php

namespace Styde\Enlighten\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Styde\Enlighten\Models\Concerns\ReadsDynamicAttributes;

class Area implements Arrayable
{
    use ReadsDynamicAttributes;


    public static function all(): Collection
    {
        $defaultView = config('enlighten.area_view');

        return collect(config('enlighten.areas'))
            ->map(function ($data) use ($defaultView) {
                return new static(
                    $data['slug'],
                    $data['name'] ?? null,
                    $data['view'] ?? $defaultView,
                );
            });
    }

    public static function get($areas): Collection
    {
        return collect($areas)
            ->map(function ($slug) {
                $config = static::getConfigFor($slug);

                return new static(
                    $slug,
                    $config['name'] ?? null,
                    $config['view'] ?? config('enlighten.area_view')
                );
            })
            ->sortBy('name')
            ->values();
    }

    public static function getConfigFor(string $areaSlug): array
    {
        return collect(config('enlighten.areas'))->firstWhere('slug', $areaSlug) ?: [];
    }

    public function __construct(string $slug, string $name = null, $view = 'features')
    {
        $this->setAttributes([
            'name' => $name ?: ucfirst(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'view' => $view,
        ]);
    }
}
