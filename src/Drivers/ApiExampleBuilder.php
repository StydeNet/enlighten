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
        // TODO: Implement addRequest() method.
    }

    public function setResponse(ResponseInfo $response, bool $followsRedirect, RouteInfo $routeInfo, array $session)
    {
        // TODO: Implement setResponse() method.
    }

    public function setException(ExceptionInfo $exceptionInfo)
    {
        // TODO: Implement setException() method.
    }

    public function addQuery(QueryExecuted $queryExecuted)
    {
        // TODO: Implement addQuery() method.
    }

    public function addSnippet($key, string $code)
    {
        // TODO: Implement addSnippet() method.
    }

    public function setSnippetResult($result)
    {
        // TODO: Implement setSnippetResult() method.
    }

    public function build(): Example
    {
        return new ApiExample;
    }
}
