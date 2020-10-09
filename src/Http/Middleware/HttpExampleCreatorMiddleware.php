<?php

namespace Styde\Enlighten\Http\Middleware;

use Closure;
use Styde\Enlighten\HttpExampleCreator;

class HttpExampleCreatorMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (app()->runningUnitTests()) {
            app(HttpExampleCreator::class)->createHttpExample($request, $response);
        }

        return $response;
    }
}
