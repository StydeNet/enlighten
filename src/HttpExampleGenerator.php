<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// @TODO: rename class because it's not generating anything anymore.
// ExampleRepository? ExampleRecorder?
class HttpExampleGenerator
{
    private TestInspector $testInspector;
    private RequestInspector $requestInspector;
    private ResponseInspector $responseInspector;
    private SessionInspector $sessionInspector;

    public function __construct(
        TestInspector $testInspector,
        RequestInspector $requestInspector,
        ResponseInspector $responseInspector,
        SessionInspector $sessionInspector
    ) {
        $this->testInspector = $testInspector;
        $this->requestInspector = $requestInspector;
        $this->responseInspector = $responseInspector;
        $this->sessionInspector = $sessionInspector;
    }

    public function createHttpExample(Request $request, Response $response)
    {
        $testMethodInfo = $this->testInspector->getCurrentTestInfo();

        if ($testMethodInfo->isExcluded()) {
            return;
        }

        $testMethodInfo->saveHttpExample(
            $this->requestInspector->getDataFrom($request),
            $this->responseInspector->getDataFrom($response),
            $this->sessionInspector->getData()
        );
    }
}
