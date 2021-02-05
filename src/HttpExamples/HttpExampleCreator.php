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

    /**
     * @var ExampleCreator
     */
    private $exampleCreator;

    /**
     * @var RequestInspector
     */
    private $requestInspector;

    /**
     * @var ResponseInspector
     */
    private $responseInspector;

    /**
     * @var SessionInspector
     */
    private $sessionInspector;

    /**
     * @var RouteInspector
     */
    private $routeInspector;

    public function __construct(
        ExampleCreator $exampleCreator,
        RequestInspector $requestInspector,
        RouteInspector $routeInspector,
        ResponseInspector $responseInspector,
        SessionInspector $sessionInspector
    ) {
        $this->exampleCreator = $exampleCreator;
        $this->requestInspector = $requestInspector;
        $this->routeInspector = $routeInspector;
        $this->responseInspector = $responseInspector;
        $this->sessionInspector = $sessionInspector;
    }

    public static function followingRedirect(Closure $callback)
    {
        static::$followsRedirect = true;

        $response = $callback();

        static::$followsRedirect = false;

        return $response;
    }

    public function createHttpExample(Request $request)
    {
        $testExample = $this->exampleCreator->getCurrentExample();

        if (is_null($testExample)) {
            return;
        }

        $testExample->addRequest(
            $this->requestInspector->getDataFrom($request)
        );
    }

    public function saveHttpResponseData(Request $request, Response $response)
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
