<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Model;

class TestMethodInfo implements TestInfo
{
    public TestClassInfo $classInfo;
    private string $methodName;
    private array $texts;
    private string $testStatus;

    public function __construct(TestClassInfo $classInfo, string $methodName, array $texts = [])
    {
        $this->classInfo = $classInfo;
        $this->methodName = $methodName;
        $this->texts = $texts;
        $this->testStatus = 'unknown';
    }

    public function isIgnored(): bool
    {
        return false;
    }

    public function addTestStatus(string $status): self
    {
        $this->testStatus = $status;

        return $this;
    }

    public function save(): Model
    {
        $group = $this->classInfo->save();

        return $group->examples()->updateOrCreate([
            'method_name' => $this->methodName,
        ], [
            // Test
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'test_status' => $this->testStatus,
        ]);
    }

    public function saveHttpExample(RequestInfo $request, ResponseInfo $response, array $session): Model
    {
        $example = $this->save();

        $example->http_data->fill([
            // Request
            'request_headers' => $request->getHeaders(),
            'request_method' => $request->getMethod(),
            'request_path' => $request->getPath(),
            'request_query_parameters' => $request->getQueryParameters(),
            'request_input' => $request->getInput(),
            // Route
            'route' => $request->routeInfo->getUri(),
            'route_parameters' => $request->routeInfo->getParameters(),
            // Response
            'response_status' => $response->getStatusCode(),
            'response_headers' => $response->getHeaders(),
            'response_body' => $response->getContent(),
            'response_template' => $response->getTemplate(),
            // Session
            'session_data' => $session,
        ])->save();

        return $example;
    }

    private function getTitle(): string
    {
        return $this->texts['title'] ?? $this->getDefaultTitle();
    }

    private function getDefaultTitle(): string
    {
        return ucfirst(str_replace('_', ' ', $this->methodName));
    }

    private function getDescription(): ?string
    {
        return $this->texts['description'] ?? null;
    }
}
