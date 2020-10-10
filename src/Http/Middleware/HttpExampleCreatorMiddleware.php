<?php

namespace Styde\Enlighten\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Styde\Enlighten\HttpExampleCreator;
use Throwable;

class HttpExampleCreatorMiddleware
{
    public function handle($request, Closure $next)
    {
        if (app()->runningUnitTests()) {
            return $this->recordExample(app(HttpExampleCreator::class), $next, $request);
        }

        return $next($request);
    }

    private function recordExample(HttpExampleCreator $httpExampleCreator, Closure $next, $request)
    {
        // Create the example and persist the request data before
        // running the actual request, so if the request fails
        // we'll at least have information about the request.
        $testMethodInfo = $httpExampleCreator->createHttpExample($request);

        $response = $next($request);

        $httpExampleCreator->saveHttpResponseData($testMethodInfo, $request, $response);

        return $response;
    }
}
