<?php

namespace Styde\Enlighten\HttpExamples;

use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use Styde\Enlighten\TestExample;
use Styde\Enlighten\TestInfo;
use Styde\Enlighten\TestInspector;
use Symfony\Component\HttpFoundation\Response;

class HttpExampleCreator
{
    /**
     * @var TestInspector
     */
    private $testInspector;

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
        TestInspector $testInspector,
        RequestInspector $requestInspector,
        RouteInspector $routeInspector,
        ResponseInspector $responseInspector,
        SessionInspector $sessionInspector
    ) {
        $this->testInspector = $testInspector;
        $this->requestInspector = $requestInspector;
        $this->routeInspector = $routeInspector;
        $this->responseInspector = $responseInspector;
        $this->sessionInspector = $sessionInspector;
    }

    public function createHttpExample(Request $request): TestInfo
    {
        $testExample = $this->testInspector->getCurrentTestExample();

        if ($testExample->isIgnored()) {
            return $testExample;
        }

        $testExample->saveRequestData(
            $this->requestInspector->getDataFrom($request)
        );

        return $testExample;
    }

    public function saveHttpResponseData(Request $request, Response $response)
    {
        $testExample = $this->testInspector->getCurrentTestExample();

        if ($testExample->isIgnored()) {
            return;
        }

        /** @var TestExample $testExample */
        $testExample->saveResponseData(
            $this->responseInspector->getDataFrom($this->normalizeResponse($response)),
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
