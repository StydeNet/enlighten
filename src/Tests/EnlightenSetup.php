<?php

namespace Styde\Enlighten\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\TestRunner;
use Styde\Enlighten\Exceptions\LaravelNotPresent;
use Styde\Enlighten\TestInspector;
use Styde\Enlighten\TestRun;

trait EnlightenSetup
{
    private static ?TestRun $testRun = null;

    private ?ExceptionRecorder $exceptionRecorder = null;

    private bool $captureQueries = true;

    public function setUpEnlighten()
    {
        if (empty($this->app)) {
            throw new LaravelNotPresent;
        }

        $this->afterApplicationCreated(function () {
            if ($this->enlightenIsDisabled()) {
                return;
            }

            $this->resetRunData();

            $this->createTestExample();

            $this->captureExceptions();

            $this->captureQueries();
        });

        $this->beforeApplicationDestroyed(function () {
            if ($this->enlightenIsDisabled()) {
                return;
            }

            $this->stopCapturingQueries();

            $this->saveTestExample();
        });
    }

    private function enlightenIsDisabled()
    {
        return ! $this->app->make('config')->get('enlighten.enabled');
    }

    private function resetRunData()
    {
        TestRun::getInstance()->reset();
    }

    private function createTestExample()
    {
        $this->app->make(TestInspector::class)->createTestExample(get_class($this), $this->getName());
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

            $this->app->make(TestInspector::class)
                ->getCurrentTestExample()
                ->saveQuery($query);
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
        if ($this->enlightenIsDisabled()) {
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
        if ($this->enlightenIsDisabled()) {
            return parent::withExceptionHandling();
        }

        $this->captureExceptions();

        $this->exceptionRecorder->forwardToOriginal();

        return $this;
    }

    protected function saveTestExample()
    {
        $test = $this->app->make(TestInspector::class)->getCurrentTestExample();

        $test->saveTestStatus($this->getStatusAsText());

        if ($this->getStatus() !== TestRunner::STATUS_PASSED) {
            TestRun::getInstance()->saveFailedTestLink($test);
        }
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
}
