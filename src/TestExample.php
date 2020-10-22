<?php

namespace Styde\Enlighten;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Validation\ValidationException;
use Styde\Enlighten\Models\ExampleSnippet;
use Styde\Enlighten\Models\ExampleSnippetCall;
use Styde\Enlighten\Models\HttpData;
use Styde\Enlighten\Models\Status;
use Throwable;
use ReflectionMethod;
use Styde\Enlighten\Models\Example;

class TestExample extends TestInfo
{
    public TestExampleGroup $classInfo;
    protected ?int $line;
    protected ?Example $example = null;
    protected string $status;
    private array $texts;
    private ?Throwable $exception = null;
    private ?HttpData $currentHttpData = null;
    protected ?ExampleSnippetCall $currentSnippetCall = null;

    public function __construct(TestExampleGroup $classInfo, string $methodName, array $texts = [])
    {
        parent::__construct($classInfo->getClassName(), $methodName);

        $this->classInfo = $classInfo;
        $this->texts = $texts;
        $this->status = 'unknown';
        $this->line = null;
    }

    public function getSignature()
    {
        return $this->classInfo->getClassName().'::'.$this->methodName;
    }

    public function getLink()
    {
        if ($this->example->group == null) {
            return null;
        }

        return $this->example->url;
    }

    public function isIgnored(): bool
    {
        return false;
    }

    public function save()
    {
        if ($this->example != null) {
            return;
        }

        $group = $this->classInfo->save();

        $this->example = Example::updateOrCreate([
            'group_id' => $group->id,
            'method_name' => $this->methodName,
        ], [
            'line' => $this->getStartLine(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'test_status' => $this->status,
        ]);
    }

    public function saveTestStatus(string $testStatus)
    {
        $this->save();

        $this->example->update(['test_status' => $testStatus]);

        if ($this->example->getStatus() !== Status::SUCCESS) {
            $this->saveExceptionData($this->exception);
        }
    }

    public function createHttpExample(RequestInfo $request)
    {
        $this->save();

        $this->currentHttpData = $this->example->http_data()->create([
            'example_id' => $this->example->id,
            'request_headers' => $request->getHeaders(),
            'request_method' => $request->getMethod(),
            'request_path' => $request->getPath(),
            'request_query_parameters' => $request->getQueryParameters(),
            'request_input' => $request->getInput(),
        ]);
    }

    public function saveResponseData(ResponseInfo $response, RouteInfo $routeInfo, array $session)
    {
        $this->save();

        $this->currentHttpData->update([
            // Route
            'route' => $routeInfo->getUri(),
            'route_parameters' => $routeInfo->getParameters(),
            // Response
            'response_status' => $response->getStatusCode(),
            'response_headers' => $response->getHeaders(),
            'response_body' => $response->getContent(),
            'response_template' => $response->getTemplate(),
            // Session
            'session_data' => $session,
        ]);

        $this->currentHttpData = null;
    }

    public function setException(?Throwable $exception)
    {
        $this->exception = $exception;
    }

    private function saveExceptionData(?Throwable $exception)
    {
        if (is_null($exception)) {
            return;
        }

        $this->example->exception->fill([
            'class_name' => get_class($exception),
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'extra' => $this->getExtraExceptionData($exception),
        ])->save();
    }

    private function getExtraExceptionData(?Throwable $exception): array
    {
        if ($exception instanceof ValidationException) {
            return [
                'errors' => $exception->errors(),
            ];
        }

        return [];
    }

    public function saveQuery(QueryExecuted $queryExecuted)
    {
        $this->save();

        $this->example->queries()->create([
            'sql' => $queryExecuted->sql,
            'bindings' => $queryExecuted->bindings,
            'time' => $queryExecuted->time,
            'http_data_id' => optional($this->currentHttpData)->id,
            'snippet_call_id' => optional($this->currentSnippetCall)->id,
        ]);
    }

    public function createSnippet(string $code)
    {
        $this->save();

        return $this->example->snippets()->create([
            'code' => $code,
        ]);
    }

    public function createSnippetCall(ExampleSnippet $snippet, array $arguments)
    {
        return $this->currentSnippetCall = $snippet->calls()->create([
            'arguments' => $arguments,
        ]);
    }

    public function saveSnippetCallResult($result)
    {
        $this->currentSnippetCall->update(['result' => $result]);
    }

    public function getTitle(): string
    {
        return $this->texts['title'] ?? $this->getDefaultTitle();
    }

    private function getDefaultTitle(): string
    {
        $str = $this->methodName;

        if (strpos($str, 'test_') === 0) {
            $str = substr($str, 5);
        }

        return ucfirst(str_replace('_', ' ', $str));
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
