<?php

namespace Styde\Enlighten\Http\Middleware;

use Closure;
use Styde\Enlighten\HttpExampleCreator;

class HttpExampleCreatorMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (app()->runningUnitTests() && $this->allowedStatus($response)) {
            app(HttpExampleCreator::class)->createHttpExample($request, $response);
        }

        return $response;
    }

    protected function allowedStatus($response): bool
    {
        return ! collect(config('enlighten.response.status.ignore'))->contains($response->status());
    }
}
