<?php

namespace Styde\Enlighten\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Styde\Enlighten\Models\Concerns\GetStats;
use Styde\Enlighten\Models\Concerns\ReadsDynamicAttributes;

class Endpoint implements Wrappable, Statable
{
    use ReadsDynamicAttributes, GetStats;

    public function __construct($method, $route, Collection $requests = null)
    {
        $this->setAttributes([
            'method' => $method,
            'route' => $route,
            'requests' => $requests ?: collect(),
        ]);
    }

    public function matches(Module $module): bool
    {
        return Str::is($module->routes, $this->route);
    }

    public function getSignature()
    {
        return "{$this->method} {$this->route}";
    }

    public function getTitle()
    {
        return $this->getMainRequest()->example->group->title;
    }

    public function getMainRequest()
    {
        return $this->requests->first();
    }

    public function getAdditionalRequests()
    {
        return $this->requests->slice(1);
    }

    public function getMethodIndex()
    {
        return array_search($this->method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE']);
    }

    public function getStats()
    {
        return $this->requests
            ->groupBy('example.status')
            ->map(fn ($endpoints, $status) => [
                'status' => $status,
                'count' => count($endpoints),
            ]);
    }
}
