<?php

namespace Styde\Enlighten\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Styde\Enlighten\ExampleCreator;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionRecorder implements ExceptionHandler
{
    private $forwardToOriginalHandler = true;

    /**
     * @var array
     */
    private $except = [];

    public function __construct(private readonly ExceptionHandler $originalHandler)
    {
    }

    public function forwardToORiginal(): void
    {
        $this->forwardToOriginalHandler = true;
        $this->except = [];
    }

    public function forceThrow(array $except = []): void
    {
        $this->forwardToOriginalHandler = false;
        $this->except = $except;
    }

    public function report(Throwable $e): void
    {
        app(ExampleCreator::class)->captureException($e);

        if ($this->forwardToOriginalHandler) {
            $this->originalHandler->report($e);
        }
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
                "{$request->method()} {$request->url()}",
                null,
                $e->getCode()
            );
        }

        throw $e;
    }

    public function renderForConsole($output, Throwable $e): void
    {
        if ($this->forwardToOriginalHandler) {
            $this->originalHandler->renderForConsole($output, $e);
            return;
        }

        (new ConsoleApplication)->renderThrowable($e, $output);
    }
}
