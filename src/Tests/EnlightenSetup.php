<?php

namespace Styde\Enlighten\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\TestRunner;
use Styde\Enlighten\ExampleCreator;
use Styde\Enlighten\Exceptions\LaravelNotPresent;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\HttpExamples\HttpExampleCreator;
use Styde\Enlighten\RunBuilder;

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

    public function setUpEnlighten()
    {
        if (empty($this->app)) {
            throw new LaravelNotPresent;
        }

        $this->afterApplicationCreated(function () {
            if (Enlighten::isDisabled()) {
                return;
            }

            $this->resetRunData();

            $this->makeExample();

            $this->captureExceptions();

            $this->captureQueries();
        });

        $this->beforeApplicationDestroyed(function () {
            if (Enlighten::isDisabled()) {
                return;
            }

            $this->stopCapturingQueries();

            $this->saveExampleStatus();
        });
    }

    private function resetRunData()
    {
        $this->app->make(RunBuilder::class)->reset();
    }

    private function makeExample()
    {
        $this->app->make(ExampleCreator::class)->makeExample(get_class($this), $this->getName(false));
    }

    private function captureQueries()
    {
        DB::listen(function ($query) {
            if (! $this->captureQueries) {
                return;
            }

            if ($query->connectionName == 'enlighten') {
                return;
            }

            $this->app->make(ExampleCreator::class)->saveQuery($query);
        });
    }

    private function stopCapturingQueries()
    {
        $this->captureQueries = false;
    }

    private function captureExceptions()
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
    protected function withoutExceptionHandling(array $except = [])
    {
        if (Enlighten::isDisabled()) {
            return parent::withoutExceptionHandling($except);
        }

        $this->captureExceptions();

        $this->exceptionRecorder->forceThrow($except);

        return $this;
    }

    /**
     * Restore exception handling.
     *
     * @return $this
     */
    protected function withExceptionHandling()
    {
        if (Enlighten::isDisabled()) {
            return parent::withExceptionHandling();
        }

        $this->captureExceptions();

        $this->exceptionRecorder->forwardToOriginal();

        return $this;
    }

    protected function saveExampleStatus()
    {
        $this->app->make(ExampleCreator::class)->saveStatus($this->getStatusAsText());
    }

    private function getStatusAsText()
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
