<?php

namespace Styde\Enlighten;

use Illuminate\Database\Events\QueryExecuted;
use Styde\Enlighten\Contracts\Example;
use Styde\Enlighten\HttpExamples\RequestInfo;
use Styde\Enlighten\HttpExamples\ResponseInfo;
use Styde\Enlighten\HttpExamples\RouteInfo;
use Throwable;

interface ExampleBuilder
{
    public function setStatus(string $testStatus, string $status);

    public function addRequest(RequestInfo $request);

    public function setResponse(ResponseInfo $response, bool $followsRedirect, RouteInfo $routeInfo, array $session);

    public function setException(string $className, ?Throwable $exception, array $extra);

    public function addQuery(QueryExecuted $queryExecuted);

    public function addSnippet($key, string $code);

    public function setSnippetResult($result);

    public function build(): Example;
}
