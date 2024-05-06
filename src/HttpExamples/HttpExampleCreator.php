<?php

namespace Styde\Enlighten\HttpExamples;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use Styde\Enlighten\ExampleCreator;
use Symfony\Component\HttpFoundation\Response;

class HttpExampleCreator
{
    /**
     * @var bool
     */
    private static $followsRedirect = false;

    public function __construct(private readonly ExampleCreator $exampleCreator, private readonly RequestInspector $requestInspector, private readonly RouteInspector $routeInspector, private readonly ResponseInspector $responseInspector, private readonly SessionInspector $sessionInspector)
    {
    }

    public static function followingRedirect(Closure $callback)
    {
        static::$followsRedirect = true;

        $response = $callback();

        static::$followsRedirect = false;

        return $response;
    }

    public function createHttpExample(Request $request): void
    {
        $testExample = $this->exampleCreator->getCurrentExample();

        if (is_null($testExample)) {
            return;
        }

        $testExample->addRequest(
            $this->requestInspector->getDataFrom($request)
        );
    }

    public function saveHttpResponseData(Request $request, Response $response): void
    {
        $testExample = $this->exampleCreator->getCurrentExample();

        if (is_null($testExample)) {
            return;
        }

        $testExample->setResponse(
            $this->responseInspector->getDataFrom($this->normalizeResponse($response)),
            static::$followsRedirect,
            $this->routeInspector->getInfoFrom($request->route()),
            $this->sessionInspector->getData()
        );
    }

    private function normalizeResponse(Response $response)
    {
        if ($response instanceof TestResponse) {
            $response = $response->baseResponse;
        }

        return $response;
    }
}
