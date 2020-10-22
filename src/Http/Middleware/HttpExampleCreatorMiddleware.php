<?php

namespace Styde\Enlighten\Http\Middleware;

use Closure;
use Styde\Enlighten\HttpExampleCreator;

class HttpExampleCreatorMiddleware
{
    /**
     * @var HttpExampleCreator
     */
    private $httpExampleCreator;

    public function __construct(HttpExampleCreator $httpExampleCreator)
    {
        $this->httpExampleCreator = $httpExampleCreator;
    }

    public function handle($request, Closure $next)
    {
        if (app()->runningUnitTests()) {
            $this->recordRequestData($request);
        }

        return $next($request);
    }

    /**
     * Create the example and persist the request data before
     * running the actual request, so if the HTTP call fails
     * we will at least have information about the request.
     *
     * @param $request
     */
    private function recordRequestData($request)
    {
        $this->httpExampleCreator->createHttpExample($request);
    }

    public function terminate($request, $response)
    {
        $this->httpExampleCreator->saveHttpResponseData($request, $response);
    }
}
