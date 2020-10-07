<?php

namespace Styde\Enlighten;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestSuite implements Arrayable
{
    public string $key;
    public string $title;
    public string $slug;

    public static function all(): Collection
    {
        if (config()->has('enlighten.test-suites')) {
            return collect(config('enlighten.test-suites'))
                ->map(function ($title, $key) {
                    return new static($key, $title);
                });
        }

        return DB::connection('enlighten')
            ->table('enlighten_example_groups')
            ->pluck('class_name')
            ->map(function ($classNames) {
                return explode('\\', $classNames)[1];
            })
            ->unique()
            ->map(function ($key) {
                return new static($key, $key);
            });
    }

    public function __construct(string $key, string $title = null)
    {
        $this->key = $key;
        $this->title = $title ?? $key;
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
