<?php

namespace Styde\Enlighten;

use Illuminate\Database\Events\QueryExecuted;
use Styde\Enlighten\HttpExamples\RequestInfo;
use Styde\Enlighten\HttpExamples\ResponseInfo;
use Styde\Enlighten\HttpExamples\RouteInfo;
use Throwable;

interface ExampleBuilder
{
    public function save();

    public function saveStatus(string $testStatus, string $status);

    public function createRequest(RequestInfo $request);

    public function saveResponse(ResponseInfo $response, bool $followsRedirect, RouteInfo $routeInfo, array $session);

    public function saveExceptionData(string $className, ?Throwable $exception, array $extra);

    public function saveQuery(QueryExecuted $queryExecuted);

    public function createSnippet($key, string $code);

    public function saveSnippetResult($result);
}
