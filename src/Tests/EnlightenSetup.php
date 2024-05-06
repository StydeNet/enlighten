<?php

namespace Styde\Enlighten\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestStatus\TestStatus;
use Styde\Enlighten\Enlighten;
use Styde\Enlighten\ExampleCreator;
use Styde\Enlighten\Exceptions\LaravelNotPresent;
use Styde\Enlighten\HttpExamples\HttpExampleCreator;

trait EnlightenSetup
{
    private ?ExceptionRecorder $exceptionRecorder = null;
    private ExceptionHandler $backupOriginalExceptionHandler;

    private bool $captureQueries = true;

    public function setUpEnlighten(): void
    {
        if (empty($this->app)) {
            throw new LaravelNotPresent;
        }

        if (Enlighten::isDocumenting()) {
            $this->afterApplicationCreated(function () {
                $this->makeExample();

                $this->captureExceptions();

                $this->captureQueries();
            });

            $this->beforeApplicationDestroyed(function () {
                $this->stopCapturingQueries();

                $this->saveExampleStatus();

                $this->restoreBackupOriginalExceptionHandler();
            });
        }
    }

    private function makeExample(): void
    {
        $this->app->make(ExampleCreator::class)->makeExample(
            get_class($this),
            $this->name(),
            $this->providedData(),
            $this->dataName()
        );
    }

    private function captureQueries(): void
    {
        DB::listen(function ($query) {
            if (! $this->captureQueries) {
                return;
            }

            if ($query->connectionName === 'enlighten') {
                return;
            }

            $this->app->make(ExampleCreator::class)->addQuery($query);
        });
    }

    private function stopCapturingQueries(): void
    {
        $this->captureQueries = false;
    }

    private function captureExceptions(): void
    {
        if ($this->exceptionRecorder === null) {
            $this->backupOriginalExceptionHandler = $this->app->make(ExceptionHandler::class);
            $this->exceptionRecorder = new ExceptionRecorder($this->backupOriginalExceptionHandler);
        }

        $this->app->instance(ExceptionHandler::class, $this->exceptionRecorder);
    }

    private function restoreBackupOriginalExceptionHandler(): void
    {
        $this->app->instance(ExceptionHandler::class, $this->backupOriginalExceptionHandler);
    }

    /**
     * Only handle the given exceptions via the exception handler.
     *
     * @param  array  $except
     * @return $this
     */
    protected function withoutExceptionHandling(array $except = []): self
    {
        if (Enlighten::isDocumenting()) {
            $this->captureExceptions();

            $this->exceptionRecorder->forceThrow($except);

            return $this;
        } else {
            return parent::withoutExceptionHandling($except);
        }
    }

    /**
     * Restore exception handling.
     *
     * @return $this
     */
    protected function withExceptionHandling(): self
    {
        if (Enlighten::isDocumenting()) {
            $this->captureExceptions();

            $this->exceptionRecorder->forwardToOriginal();

            return $this;
        } else {
            return parent::withExceptionHandling();
        }
    }

    protected function saveExampleStatus(): void
    {
        $exampleCreator = $this->app->make(ExampleCreator::class);

        $exampleCreator->setStatus($this->getStatusAsText());
        $exampleCreator->build();
    }

    private function getStatusAsText(): string
    {
        return $this->status()->asString();
    }

    /**
     * Follow a redirect chain until a non-redirect is received.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Http\Response|\Illuminate\Testing\TestResponse
     */
    protected function followRedirects($response)
    {
        return HttpExampleCreator::followingRedirect(function () use ($response) {
            return parent::followRedirects($response);
        });
    }
}
