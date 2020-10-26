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
                ->map(function ($title, $key) {
                    return is_int($key)
                        ? new static($title)
                        : new static($key, $title);
                });
        }

        return DB::connection('enlighten')
            ->table('enlighten_example_groups')
            ->distinct('area')
            ->pluck('area')
            ->map(function ($key) {
                return new static($key);
            });
    }

    public function __construct(string $key, string $title = null)
    {
        $this->key = $key;
        $this->title = $title ?: ucfirst(str_replace('-', ' ', $key));
        $this->slug = Str::slug($key);
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }
}
