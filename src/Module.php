<?php

namespace Styde\Enlighten;

use Illuminate\Support\Collection;

class Module
{
    public string $name;

    /**
     * @var string|array
     */
    public $pattern;

    public Collection $groups;

    public static function all()
    {
        return ModuleCollection::make(config('enlighten.modules'))
            ->map(function ($item) {
                return new static($item['name'], $item['pattern']);
            });
    }

    public function __construct(string $name, $pattern = [])
    {
        $this->name = $name;
        $this->pattern = $pattern;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    public function addGroups(Collection $groups): void
    {
        $this->groups = $groups;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function getPassingTestsCount() : int
    {
       return $this->groups
            ->pluck('stats')
            ->flatten(1)
            ->where('test_status', 'passed')
            ->sum('count');
    }

    public function getTestsCount() : int
    {
        return $this->groups
            ->pluck('stats')
            ->flatten(1)
            ->sum('count');
    }

    public function getStatus() : string
    {
        $groupStats = $this->groups->pluck('stats')->flatten(1);

        if ($this->getPassingTestsCount() === $this->getTestsCount()) {
            return 'passed';
        }

        if ($groupStats->whereIn('test_status', ['failed', 'error'])->isNotEmpty()) {
            return 'failed';
        }

        return 'warned';
    }
}
