<?php

namespace Styde\Enlighten\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use PHPUnit\Framework\TestCase;
use Styde\Enlighten\TestInspector;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Console\Application as ConsoleApplication;
use Throwable;

class ExceptionRecorder implements ExceptionHandler
{
    private TestCase $testCase;
    private ExceptionHandler $originalHandler;

    private $forwardToOriginalHandler = true;
    private array $except = [];

    public function __construct(TestCase $testCase, ExceptionHandler $originalHandler)
    {
        $this->testCase = $testCase;
        $this->originalHandler = $originalHandler;
    }

    public function forwardToORiginal()
    {
        $this->forwardToOriginalHandler = true;
        $this->except = [];
    }

    public function forceThrow(array $except = [])
    {
        $this->forwardToOriginalHandler = false;
        $this->except = $except;
    }

    public function report(Throwable $e)
    {
        $this->captureException($e);

        if ($this->forwardToOriginalHandler) {
            $this->originalHandler->report($e);
        }
    }

    private function captureException(Throwable $e): void
    {
        $testMethodInfo = app(TestInspector::class)->getInfo(
            get_class($this->testCase), $this->testCase->getName()
        );

        if ($testMethodInfo->isIgnored()) {
            return;
        }

        // We will save the exception in memory without persiting it to the DB
        // until we get the final result from test. So, we will only persist
        // the exception data in the database if the test did not succeed.
        $testMethodInfo->setException($e);
    }

    public function shouldReport(Throwable $e)
    {
        if ($this->forwardToOriginalHandler) {
            return $this->originalHandler->shouldReport($e);
        }

        return false;
    }

    public function render($request, Throwable $e)
    {
        if ($this->forwardToOriginalHandler) {
            return $this->originalHandler->render($request, $e);
        }

        foreach ($this->except as $class) {
            if ($e instanceof $class) {
                return $this->originalHandler->render($request, $e);
            }
        }

        if ($e instanceof NotFoundHttpException) {
            throw new NotFoundHttpException(
                "{$request->method()} {$request->url()}", null, $e->getCode()
            );
        }

        throw $e;
    }

    public function renderForConsole($output, Throwable $e)
    {
        if ($this->forwardToOriginalHandler) {
            $this->originalHandler->renderForConsole($output, $e);
            return;
        }

        (new ConsoleApplication)->renderThrowable($e, $output);
    }
}
