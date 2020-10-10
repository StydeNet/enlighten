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
        $testMethodInfo = $this->testInspector->getCurrentTestInfo();

        if ($testMethodInfo->isIgnored()) {
            return $testMethodInfo;
        }

        $testMethodInfo->createHttpExample(
            $this->requestInspector->getDataFrom($request)
        );

        return $testMethodInfo;
    }

    // @TODO: rename method.
    public function saveHttpResponseData(TestInfo $testMethodInfo, Request $request, Response $response)
    {
        if ($testMethodInfo->isIgnored()) {
            return;
        }

        $testMethodInfo->saveResponseData(
            $this->responseInspector->getDataFrom($response),
            $this->routeInspector->getInfoFrom($request->route()),
            $this->sessionInspector->getData()
        );

        $testMethodInfo->saveExceptionData($response->exception);
    }
}
