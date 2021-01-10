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

class DatabaseExampleBuilder implements ExampleBuilder
{
    /**
     * @var ExampleGroupBuilder
     */
    public $exampleGroupBuilder;

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
     * @var string
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var int
     */
    protected $order_num;

    /**
     * @var int
     */
    protected $line;

    /**
     * @var Collection
     */
    private $currentRequests;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var \Styde\Enlighten\Models\ExampleSnippet
     */
    private $currentSnippet = null;

    public function __construct()
    {
        $this->currentRequests = new Collection;
    }

    public function setExampleGroupBuilder(ExampleGroupBuilder $exampleGroupCreator): self
    {
        $this->exampleGroupBuilder = $exampleGroupCreator;

        return $this;
    }

    public function save()
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
            'description' => $this->description,
            'order_num' => $this->order_num,
            'line' => $this->line,
            'test_status' => Status::UNKNOWN,
            'status' => Status::UNKNOWN,
        ]);
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setOrderNum(int $order_num): self
    {
        $this->order_num = $order_num;
        return $this;
    }

    public function setLine(int $line): self
    {
        $this->line = $line;
        return $this;
    }

    public function setMethodName(string $methodName): self
    {
        $this->methodName = $methodName;
        return $this;
    }
}
