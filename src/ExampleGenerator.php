<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// @TODO: rename class because it's not generating anything anymore.
// ExampleRepository? ExampleRecorder?
class ExampleGenerator
{
    protected array $exclude;
    private TestInspector $testInspector;
    private RequestInspector $requestInspector;
    private ResponseInspector $responseInspector;

    public function __construct(array $config, TestInspector $testInspector, RequestInspector $requestInspector, ResponseInspector $responseInspector)
    {
        $this->exclude = $config['exclude'];
        $this->testInspector = $testInspector;
        $this->requestInspector = $requestInspector;
        $this->responseInspector = $responseInspector;
    }

    public function generateExample(Request $request, Response $response)
    {
        $testMethodInfo = $this->testInspector->getInfo();

        if ($testMethodInfo->isExcluded($this->exclude)) {
            return;
        }

        $requestInfo = $this->requestInspector->getInfoFrom($request);

        $responseInfo = $this->responseInspector->getInfoFrom($response);

        $group = ExampleGroup::updateOrCreate([
            'class_name' => $testMethodInfo->classInfo->getClassName(),
        ], [
            'title' => $testMethodInfo->classInfo->getTitle(),
            'description' => $testMethodInfo->classInfo->getDescription(),
        ]);

        $group->examples()->updateOrCreate([
            'method_name' => $testMethodInfo->getMethodName(),
        ], [
            // Test
            'title' => $testMethodInfo->getTitle(),
            'description' => $testMethodInfo->getDescription(),
            // Request
            'request_headers' => $requestInfo->getHeaders(),
            'request_method' => $requestInfo->getMethod(),
            'request_path' => $requestInfo->getPath(),
            'request_query_parameters' => $requestInfo->getQueryParameters(),
            'request_input' => $requestInfo->getInput(),
            // Route
            'route' => $requestInfo->routeInfo->getUri(),
            'route_parameters' => $requestInfo->routeInfo->getParameters(),
            // Response
            'response_status' => $responseInfo->getStatusCode(),
            'response_headers' => $responseInfo->getHeaders(),
            'response_body' => $responseInfo->getContent(),
            'response_template' => $responseInfo->getTemplate(),
        ]);
    }
}
