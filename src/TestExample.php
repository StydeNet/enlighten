<?php

namespace Styde\Enlighten;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleRequest;
use Styde\Enlighten\Models\Status;
use Throwable;

class TestExample extends TestInfo
{
    /**
     * @var TestExampleGroup
     */
    public $classInfo;

    /**
     * @var int|null
     */
    protected $line;

    /**
     * @var Example|null
     */
    protected $example = null;

    /**
     * @var array
     */
    private $texts;

    /**
     * @var Throwable|null
     */
    private $exception = null;

    /**
     * @var ExampleRequest|null
     */
    private $currentRequest = null;

    /**
     * @var \Styde\Enlighten\Models\ExampleSnippet
     */
    private $currentSnippet = null;

    public function __construct(TestExampleGroup $classInfo, string $methodName, array $texts = [])
    {
        parent::__construct($classInfo->getClassName(), $methodName);

        $this->classInfo = $classInfo;
        $this->texts = $texts;
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
            'slug' => Enlighten::generateSlugFromMethodName($this->methodName),
            'line' => $this->getStartLine(),
            'title' => $this->texts['title'] ?? Enlighten::generateTitleFromMethodName($this->methodName),
            'description' => $this->texts['description'] ?? null,
            'test_status' => Status::UNKNOWN,
            'status' => Status::UNKNOWN,
        ]);
    }

    public function saveTestStatus(string $testStatus)
    {
        $this->save();

        $this->example->update([
            'test_status' => $testStatus,
            'status' => Status::fromTestStatus($testStatus),
        ]);

        if ($this->example->getStatus() !== Status::SUCCESS) {
            $this->saveExceptionData($this->exception);
        }
    }

    public function createHttpExample(RequestInfo $request)
    {
        $this->save();

        $this->currentRequest = $this->example->requests()->create([
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

        $this->currentRequest->update([
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

        $this->currentRequest = null;
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
            'request_id' => optional($this->currentRequest)->id,
            'snippet_id' => optional($this->currentSnippet)->id,
        ]);
    }

    public function createSnippet(string $code)
    {
        $this->save();

        $this->currentSnippet = $this->example->snippets()->create([
            'code' => $code,
        ]);
    }

    public function saveSnippetResult($result)
    {
        $this->currentSnippet->update(['result' => $result]);

        $this->currentSnippet = null;
    }

    private function getStartLine()
    {
        $reflection = new ReflectionMethod($this->className, $this->methodName);

        return $reflection->getStartLine();
    }
}
