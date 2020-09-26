<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// @TODO: rename class because it's not generating anything anymore.
// ExampleRepository? ExampleRecorder?
class ExampleGenerator
{
    private TestInspector $testInspector;
    private RequestInspector $requestInspector;
    private ResponseInspector $responseInspector;

    public function __construct(
        TestInspector $testInspector,
        RequestInspector $requestInspector,
        ResponseInspector $responseInspector
    ) {
        $this->testInspector = $testInspector;
        $this->requestInspector = $requestInspector;
        $this->responseInspector = $responseInspector;
    }

    public function generateExample(Request $request, Response $response)
    {
        $testMethodInfo = $this->testInspector->getInfo();

        if ($testMethodInfo->isExcluded()) {
            return;
        }

        $requestInfo = $this->requestInspector->getInfoFrom($request);

        $responseInfo = $this->responseInspector->getInfoFrom($response);

        $testMethodInfo->save($requestInfo, $responseInfo);
    }
}
