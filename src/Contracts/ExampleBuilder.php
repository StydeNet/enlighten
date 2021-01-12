<?php

namespace Styde\Enlighten\Contracts;

use Illuminate\Database\Events\QueryExecuted;
use Styde\Enlighten\ExceptionInfo;
use Styde\Enlighten\HttpExamples\RequestInfo;
use Styde\Enlighten\HttpExamples\ResponseInfo;
use Styde\Enlighten\HttpExamples\RouteInfo;

interface ExampleBuilder
{
    public function setStatus(string $testStatus, string $status);

    public function addRequest(RequestInfo $request);

    public function setResponse(ResponseInfo $response, bool $followsRedirect, RouteInfo $routeInfo, array $session);

    public function setException(ExceptionInfo $exceptionInfo);

    public function addQuery(QueryExecuted $queryExecuted);

    public function addSnippet($key, string $code);

    public function setSnippetResult($result);

    public function build(): Example;
}
