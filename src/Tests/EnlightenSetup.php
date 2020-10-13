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

    private $captureQueries = true;

    public function setUpEnlighten()
    {
        if (is_null($this->app)) {
            throw new LaravelNotPresent;
        }

        $this->afterApplicationCreated(function () {
            if ($this->enlightenIsDisabled()) {
                return;
            }

            $this->restoreTestRun();

            $this->resetRunData();

            $this->captureExceptions();

            $this->captureQueries();
        });

        $this->beforeApplicationDestroyed(function () {
            if ($this->enlightenIsDisabled()) {
                return;
            }

            $this->stopCapturingQueries();

            $this->saveTestExample();

            $this->preserveTestRun();
        });
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

            $test = $this->app->make(TestInspector::class)
                ->getInfo(get_class($this), $this->getName());

            if ($test->isIgnored()) {
                return;
            }

            $test->saveQuery($query, $this->app->make(TestRun::class)->getContext());
        });
    }

    private function stopCapturingQueries()
    {
        $this->captureQueries = false;
    }

    private function enlightenIsDisabled()
    {
        return ! $this->app->make('config')->get('enlighten.enabled');
    }

    private function captureExceptions()
    {
        if ($this->exceptionRecorder) {
            return;
        }

        $originalExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->exceptionRecorder = new ExceptionRecorder($this, $originalExceptionHandler);

        $this->app->instance(ExceptionHandler::class, $this->exceptionRecorder);
    }

    protected function withoutExceptionHandling(array $except = [])
    {
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
        $this->captureExceptions();

        $this->exceptionRecorder->forwardToOriginal();

        return $this;
    }

    private function preserveTestRun()
    {
        static::$testRun = $this->app->make(TestRun::class);
    }

    private function restoreTestRun()
    {
        if (static::$testRun) {
            $this->app->instance(TestRun::class, static::$testRun);
        }
    }

    private function resetRunData()
    {
        $this->app->make(TestRun::class)->reset();
    }

    protected function saveTestExample()
    {
        $test = $this->app->make(TestInspector::class)
            ->getInfo(get_class($this), $this->getName());

        if ($test->isIgnored()) {
            return;
        }

        $test->saveTestStatus($this->getStatusAsText());

        if ($this->getStatus() !== TestRunner::STATUS_PASSED) {
            TestRun::saveFailedTestLink($test);
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
