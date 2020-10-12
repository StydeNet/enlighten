<?php

namespace Styde\Enlighten\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Styde\Enlighten\HttpExampleCreator;
use Styde\Enlighten\TestRun;
use Throwable;

class HttpExampleCreatorMiddleware
{
    public function handle($request, Closure $next)
    {
        if (app()->runningUnitTests()) {
            return $this->recordRequestData($next, $request);
        }

        return $next($request);
    }

    private function recordRequestData(Closure $next, $request)
    {
        // Create the example and persist the request data before
        // running the actual request, so if the HTTP call fails
        // we will at least have information about the request.
        app(HttpExampleCreator::class)->createHttpExample($request);

        return $next($request);
    }

    public function terminate($request, $response)
    {
        app(HttpExampleCreator::class)->saveHttpResponseData($request, $response);
    }
}
