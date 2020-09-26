<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Model;

class TestMethodInfo implements TestInfo
{
    public TestClassInfo $classInfo;
    private string $methodName;
    private array $texts;

    public function __construct(TestClassInfo $classInfo, string $methodName, array $texts = [])
    {
        $this->classInfo = $classInfo;
        $this->methodName = $methodName;
        $this->texts = $texts;
    }

    public function isExcluded(): bool
    {
        return false;
    }

    public function save(RequestInfo $request, ResponseInfo $response): Model
    {
        $group = $this->classInfo->save();

        return $group->examples()->updateOrCreate([
            'method_name' => $this->methodName,
        ], [
            // Test
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
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
        ]);
    }

    protected function getTitle(): string
    {
        return $this->texts['title'] ?? $this->getDefaultTitle();
    }

    protected function getDefaultTitle(): string
    {
        return ucfirst(str_replace('_', ' ', $this->methodName));
    }

    protected function getDescription(): ?string
    {
        return $this->texts['description'] ?? null;
    }
}
