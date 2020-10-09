<?php

namespace Styde\Enlighten;

use ReflectionMethod;
use Styde\Enlighten\Models\Example;

class TestMethodInfo extends TestInfo
{
    public TestClassInfo $classInfo;
    protected ?int $line;
    protected ?Example $example = null;
    private array $texts;
    private string $status;

    public function __construct(TestClassInfo $classInfo, string $methodName, array $texts = [])
    {
        parent::__construct($classInfo->getClassName(), $methodName);

        $this->classInfo = $classInfo;
        $this->texts = $texts;
        $this->status = 'unknown';
        $this->line = null;
    }

    public function isIgnored(): bool
    {
        return false;
    }

    public function addStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function save(): Example
    {
        if ($this->example != null) {
            $this->example->update(['test_status' => $this->status]);

            return $this->example;
        }

        $group = $this->classInfo->save();

        return $this->example = Example::updateOrCreate([
            'group_id' => $group->id,
            'method_name' => $this->methodName,
        ], [
            'line' => $this->getStartLine(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'test_status' => $this->status,
        ]);
    }

    public function saveHttpExample(RequestInfo $request, ResponseInfo $response, array $session): Example
    {
        $this->save();

        $this->example->http_data->fill([
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

        return $this->example;
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

    private function getStartLine()
    {
        $reflection = new ReflectionMethod($this->className, $this->methodName);

        return $reflection->getStartLine();
    }
}
