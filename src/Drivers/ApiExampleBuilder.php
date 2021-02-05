<?php

namespace Styde\Enlighten\Drivers;

use Illuminate\Database\Events\QueryExecuted;
use Styde\Enlighten\Contracts\Example;
use Styde\Enlighten\ExceptionInfo;
use Styde\Enlighten\HttpExamples\RequestInfo;
use Styde\Enlighten\HttpExamples\ResponseInfo;
use Styde\Enlighten\HttpExamples\RouteInfo;

class ApiExampleBuilder extends BaseExampleBuilder
{
    /**
     * @var ApiExampleGroupBuilder
     */
    private $exampleGroupBuilder;

    public function __construct(ApiExampleGroupBuilder $exampleGroupBuilder)
    {
        $this->exampleGroupBuilder = $exampleGroupBuilder;
    }

    public function addRequest(RequestInfo $request)
    {
        // Collect info here
    }

    public function setResponse(ResponseInfo $response, bool $followsRedirect, RouteInfo $routeInfo, array $session)
    {
        // Collect info here
    }

    public function setException(ExceptionInfo $exceptionInfo)
    {
        // Collect info here
    }

    public function addQuery(QueryExecuted $queryExecuted)
    {
        // Collect info here
    }

    public function addSnippet($key, string $code)
    {
        // Collect info here
    }

    public function setSnippetResult($result)
    {
        // Collect info here
    }

    public function build(): Example
    {
        // Send here? (requests, responses, exceptions, queries, snippets, status)

        return new ApiExample;
    }
}
