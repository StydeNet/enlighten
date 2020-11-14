<?php

namespace Styde\Enlighten;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Collection;
use Styde\Enlighten\HttpExamples\RequestInfo;
use Styde\Enlighten\HttpExamples\ResponseInfo;
use Styde\Enlighten\HttpExamples\RouteInfo;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\Status;
use Throwable;

class ExampleBuilder
{
    /**
     * @var ExampleGroupCreator
     */
    public $exampleGroupCreator;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var Example|null
     */
    protected $example = null;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var Collection
     */
    private $currentRequests;

    /**
     * @var \Styde\Enlighten\Models\ExampleSnippet
     */
    private $currentSnippet = null;

    public function __construct(ExampleGroupCreator $exampleGroupCreator, string $methodName, array $attributes = [])
    {
        $this->exampleGroupCreator = $exampleGroupCreator;
        $this->methodName = $methodName;
        $this->currentRequests = new Collection;
        $this->attributes = $attributes;
    }

    public function save()
    {
        if ($this->example != null) {
            return;
        }

        $group = $this->exampleGroupCreator->save();

        $this->example = Example::updateOrCreate([
            'group_id' => $group->id,
            'method_name' => $this->methodName,
        ], array_merge($this->attributes, [
            'test_status' => Status::UNKNOWN,
            'status' => Status::UNKNOWN,
        ]));
    }

    public function saveStatus(string $testStatus, string $status)
    {
        $this->save();

        $this->example->update([
            'test_status' => $testStatus,
            'status' => $status,
        ]);

        return $this->example;
    }

    public function createRequest(RequestInfo $request)
    {
        $this->save();

        $this->currentRequests->push($this->example->requests()->create([
            'example_id' => $this->example->id,
            'request_headers' => $request->getHeaders(),
            'request_method' => $request->getMethod(),
            'request_path' => $request->getPath(),
            'request_query_parameters' => $request->getQueryParameters(),
            'request_input' => $request->getInput(),
        ]));
    }

    public function saveResponse(ResponseInfo $response, bool $followsRedirect, RouteInfo $routeInfo, array $session)
    {
        $this->save();

        $this->currentRequests->pop()->update([
            // Route
            'route' => $routeInfo->getUri(),
            'route_parameters' => $routeInfo->getParameters(),
            // Response
            'response_status' => $response->getStatusCode(),
            'follows_redirect' => $followsRedirect,
            'response_headers' => $response->getHeaders(),
            'response_body' => $response->getContent(),
            'response_template' => $response->getTemplate(),
            // Session
            'session_data' => $session,
        ]);
    }

    public function saveExceptionData(string $className, ?Throwable $exception, array $extra)
    {
        $this->example->exception->fill([
            'class_name' => $className,
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'extra' => $extra,
        ])->save();
    }

    public function saveQuery(QueryExecuted $queryExecuted)
    {
        $this->save();

        $this->example->queries()->create([
            'sql' => $queryExecuted->sql,
            'bindings' => $queryExecuted->bindings,
            'time' => $queryExecuted->time,
            'request_id' => optional($this->currentRequests->last())->id,
            'snippet_id' => optional($this->currentSnippet)->id,
        ]);
    }

    public function createSnippet($key, string $code)
    {
        $this->save();

        $this->currentSnippet = $this->example->snippets()->create([
            'key' => $key,
            'code' => $code,
        ]);
    }

    public function saveSnippetResult($result)
    {
        $this->currentSnippet->update(['result' => $result]);

        $this->currentSnippet = null;
    }
}
