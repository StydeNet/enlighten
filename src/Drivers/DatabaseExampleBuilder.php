<?php

namespace Styde\Enlighten\Drivers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Collection;
use Styde\Enlighten\Contracts\Example as ExampleContract;
use Styde\Enlighten\ExceptionInfo;
use Styde\Enlighten\HttpExamples\RequestInfo;
use Styde\Enlighten\HttpExamples\ResponseInfo;
use Styde\Enlighten\HttpExamples\RouteInfo;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\Status;

class DatabaseExampleBuilder extends BaseExampleBuilder
{
    /**
     * @var DatabaseExampleGroupBuilder
     */
    private $exampleGroupBuilder;

    /**
     * @var Example|null
     */
    private $example = null;

    /**
     * @var Collection
     */
    private $currentRequests;

    /**
     * @var \Styde\Enlighten\Models\ExampleSnippet
     */
    private $currentSnippet = null;

    public function __construct(DatabaseExampleGroupBuilder $exampleGroupBuilder)
    {
        $this->currentRequests = new Collection;
        $this->exampleGroupBuilder = $exampleGroupBuilder;
    }

    public function addRequest(RequestInfo $request)
    {
        $this->save();

        $this->currentRequests->push($this->example->requests()->create([
            'example_id' => $this->example->id,
            'request_headers' => $request->getHeaders(),
            'request_method' => $request->getMethod(),
            'request_path' => $request->getPath(),
            'request_query_parameters' => $request->getQueryParameters(),
            'request_input' => $request->getInput(),
            'request_files' => $request->getFiles(),
        ]));
    }

    public function setResponse(ResponseInfo $response, bool $followsRedirect, RouteInfo $routeInfo, array $session)
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

    public function setException(ExceptionInfo $exception)
    {
        $this->example->exception->fill([
            'class_name' => $exception->getClassName(),
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'extra' => $exception->getData(),
        ])->save();
    }

    public function addQuery(QueryExecuted $queryExecuted)
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

    public function addSnippet($key, string $code)
    {
        $this->save();

        $this->currentSnippet = $this->example->snippets()->create([
            'key' => $key,
            'code' => $code,
        ]);
    }

    public function setSnippetResult($result)
    {
        $this->currentSnippet->update(['result' => $result]);

        $this->currentSnippet = null;
    }

    public function build(): ExampleContract
    {
        $this->save();

        $this->example->update([
            'test_status' => $this->testStatus,
            'status' => $this->status,
        ]);

        return $this->example;
    }

    private function save()
    {
        if ($this->example != null) {
            return;
        }

        $group = $this->exampleGroupBuilder->save();

        $this->example = Example::create([
            'group_id' => $group->id,
            'method_name' => $this->methodName,
            'slug' => $this->slug,
            'title' => $this->title,
            'data_name' => $this->dataName,
            'provided_data' => $this->providedData,
            'description' => $this->description,
            'order_num' => $this->order_num,
            'line' => $this->line,
            'test_status' => Status::UNKNOWN,
            'status' => Status::UNKNOWN,
        ]);
    }
}
