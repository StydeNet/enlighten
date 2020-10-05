<?php

namespace Styde\Enlighten;

use Illuminate\Support\Collection;

class Module
{
    use ReadsDynamicAttributes;

    public static function all()
    {
        return ModuleCollection::make(config('enlighten.modules'))
            ->map(function ($item) {
                return new static($item['name'], $item['pattern']);
            });
    }

    public function __construct(string $name, $pattern = [])
    {
        $this->setAttributes([
            'name' => $name,
            'pattern' => $pattern,
        ]);
    }

    public function addGroups(Collection $groups): void
    {
        $this->attributes['groups'] = $groups;
    }

    public function getPassingTestsCount(): int
    {
       return $this->groups->pluck('passing_tests_count')->sum();
    }

    public function getTestsCount(): int
    {
        return $this->groups->pluck('tests_count')->sum();
    }

    public function getStatus(): string
    {
        if ($this->getPassingTestsCount() === $this->getTestsCount()) {
            return 'passed';
        }

        if ($this->groups->whereIn('status', ['failed', 'error'])->isNotEmpty()) {
            return 'failed';
        }

        return 'warned';
    }

    public function getPassed(): bool
    {
        return $this->status === 'passed';
    }
}
