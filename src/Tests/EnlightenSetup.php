<?php

namespace Styde\Enlighten\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use PHPUnit\TextUI\TestRunner;
use Styde\Enlighten\TestInspector;
use Styde\Enlighten\TestRun;

trait EnlightenSetup
{
    private static ?TestRun $testRun = null;

    private ?ExceptionRecorder $exceptionRecorder = null;

    public function setUpEnlighten()
    {
        if (! $this->app->make('config')->get('enlighten.enabled')) {
            return;
        }

        $this->beforeApplicationDestroyed(fn() => $this->saveTestExample());

        $this->beforeApplicationDestroyed(fn() => $this->preserveTestRun());

        $this->afterApplicationCreated(function () {
            $this->restoreTestRun();

            $this->app->make(TestRun::class)->reset();
        });

        $this->captureExceptions();
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

    /**
     * Disable exception handling for the test.
     *
     * @param  array  $except
     * @return $this
     */
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

    protected function saveTestExample()
    {
        $test = $this->app->make(TestInspector::class)
            ->getInfo(get_class($this), $this->getName());

        if ($test->isIgnored()) {
            return;
        }

        $test->saveTestStatus($this->getStatusAsText());
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
