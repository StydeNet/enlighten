<?php

namespace Styde\Enlighten\HttpExamples;

use Illuminate\Routing\Route;

class RouteInspector
{
    public function getInfoFrom(?Route $route): RouteInfo
    {
        if (is_null($route)) {
            return new RouteInfo(null);
        }

        return new RouteInfo($route->uri(), $this->getParameters($route));
    }

    /**
     * Get all the route parameters as keys and the parameter-where conditions as values.
     *
     * @return array
     */
    protected function getParameters(Route $route): array
    {
        return collect($route->parameterNames())
            ->mapWithKeys(function ($parameter) {
                return [$parameter => '*'];
            })
            ->merge(
                array_intersect_key($route->wheres, $route->originalParameters())
            )
            ->map(function ($pattern, $name) use ($route) {
                return [
                    'name' => $name,
                    'pattern' => $pattern,
                    'optional' => $this->isParameterOptional($route, $name),
                ];
            })
            ->values()
            ->all();
    }

    protected function isParameterOptional(Route $route, $parameter): bool
    {
        return (bool) preg_match("/{{$parameter}\?}/", $route->uri());
    }
}
