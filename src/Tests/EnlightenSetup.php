<?php

namespace Styde\Enlighten\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\TestRunner;
use Styde\Enlighten\ExampleCreator;
use Styde\Enlighten\Exceptions\LaravelNotPresent;
use Styde\Enlighten\HttpExamples\HttpExampleCreator;

trait EnlightenSetup
{
    /**
     * @var ExceptionRecorder|null
     */
    private $exceptionRecorder = null;

    /**
     * @var bool
     */
    private $captureQueries = true;

    public function setUpEnlighten(): void
    {
        if (empty($this->app)) {
            throw new LaravelNotPresent;
        }

        if (Recording::isEnabled()) {
            $this->afterApplicationCreated(function () {
                $this->makeExample();

                $this->captureExceptions();

                $this->captureQueries();
            });

            $this->beforeApplicationDestroyed(function () {
                $this->stopCapturingQueries();

                $this->saveExampleStatus();
            });
        }
    }

    private function makeExample(): void
    {
        $this->app->make(ExampleCreator::class)->makeExample(get_class($this), $this->getName(false));
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
        // This setup only needs to run once.
        if ($this->exceptionRecorder) {
            return;
        }

        $originalExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->exceptionRecorder = new ExceptionRecorder($originalExceptionHandler);

        $this->app->instance(ExceptionHandler::class, $this->exceptionRecorder);
    }

    /**
     * Only handle the given exceptions via the exception handler.
     *
     * @param  array  $except
     * @return $this
     */
    protected function withoutExceptionHandling(array $except = []): self
    {
        if (Recording::isEnabled()) {
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
        if (Recording::isEnabled()) {
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
        $statuses = [
            TestRunner::STATUS_PASSED => 'passed',
            TestRunner::STATUS_SKIPPED => 'skipped',
            TestRunner::STATUS_INCOMPLETE => 'incomplete',
            TestRunner::STATUS_FAILURE => 'failure',
            TestRunner::STATUS_ERROR => 'error',
            TestRunner::STATUS_RISKY => 'risky',
            TestRunner::STATUS_WARNING => 'warning',
        ];

        return $statuses[$this->getStatus()] ?? 'unknown';
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
