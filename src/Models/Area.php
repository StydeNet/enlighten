<?php

namespace Styde\Enlighten\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Area implements Arrayable
{
    /**
     * @var string
     */
    public $key;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $slug;

    public static function all(): Collection
    {
        if (config()->has('enlighten.areas')) {
            return collect(config('enlighten.areas'))
                ->map(function ($value, $key) {
                    return is_int($key)
                        ? new static($value)
                        : new static($key, $value);
                });
        }

        return DB::connection('enlighten')
            ->table('enlighten_example_groups')
            ->distinct('area')
            ->pluck('area')
            ->map(function ($area) {
                return new static($area);
            });
    }

    public function __construct(string $slug, string $title = null)
    {
        $this->title = $title ?: ucfirst(str_replace('-', ' ', $slug));
        $this->slug = Str::slug($slug);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }
}
