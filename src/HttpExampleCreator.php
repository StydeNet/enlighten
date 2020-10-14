<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpExampleCreator
{
    private TestInspector $testInspector;
    private RequestInspector $requestInspector;
    private ResponseInspector $responseInspector;
    private SessionInspector $sessionInspector;
    private RouteInspector $routeInspector;

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

        $testExample->createHttpExample(
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

        $testExample->saveResponseData(
            $this->responseInspector->getDataFrom($response),
            $this->routeInspector->getInfoFrom($request->route()),
            $this->sessionInspector->getData()
        );
    }
}
