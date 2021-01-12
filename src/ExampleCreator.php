<?php

namespace Styde\Enlighten;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Styde\Enlighten\Models\Status;
use Styde\Enlighten\Tests\TestRun;
use Styde\Enlighten\Utils\Annotations;
use Throwable;

class ExampleCreator
{
    const LAST_ORDER_POSITION = 9999;

    /**
     * @var bool
     */
    protected $missingSetup = true;

    /**
     * @var TestRun
     */
    protected $testRun;

    /**
     * @var RunBuilder
     */
    private $runBuilder;

    /**
     * @var ExampleGroupBuilder|null
     */
    protected static $currentExampleGroupBuilder = null;

    /**
     * @var ExampleBuilder|null
     */
    protected $currentExampleBuilder = null;

    /**
     * @var Throwable
     */
    protected $currentException;

    /**
     * @var Annotations
     */
    protected $annotations;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var ExampleProfile
     */
    private $profile;

    public static function clearExampleGroupBuilder()
    {
        static::$currentExampleGroupBuilder = null;
    }

    public function __construct(TestRun $testRun, RunBuilder $runBuilder, Annotations $annotations, Settings $settings, ExampleProfile $profile)
    {
        $this->testRun = $testRun;
        $this->runBuilder = $runBuilder;
        $this->annotations = $annotations;
        $this->settings = $settings;
        $this->profile = $profile;
    }

    public function getCurrentExample(): ?ExampleBuilder
    {
        if ($this->missingSetup) {
            $this->testRun->reportMissingSetup();
        }

        return $this->currentExampleBuilder;
    }

    public function makeExample(string $className, string $methodName)
    {
        $this->missingSetup = false;
        $this->currentExampleBuilder = null;
        $this->currentException = null;

        $classAnnotations = $this->annotations->getFromClass($className);
        $methodAnnotations = $this->annotations->getFromMethod($className, $methodName);

        $options = array_merge($classAnnotations->get('enlighten', []), $methodAnnotations->get('enlighten', []));

        if ($this->profile->shouldIgnore($className, $methodName, $options)) {
            return;
        }

        $exampleGroupBuilder = $this->getExampleGroup($className, $classAnnotations);

        $this->currentExampleBuilder = $exampleGroupBuilder->newExample()
            ->setMethodName($methodName)
            ->setSlug($this->settings->generateSlugFromMethodName($methodName))
            ->setTitle($this->getTitleFor('method', $methodAnnotations, $methodName))
            ->setDescription($methodAnnotations->get('description'))
            ->setLine($this->getStartLine($className, $methodName))
            ->setOrderNum($methodAnnotations->get('enlighten')['order'] ?? self::LAST_ORDER_POSITION);
    }

    public function addQuery(QueryExecuted $query)
    {
        if ($this->shouldIgnore()) {
            return;
        }

        $this->currentExampleBuilder->addQuery($query);
    }

    public function captureException(Throwable $exception)
    {
        if ($this->shouldIgnore()) {
            return;
        }

        // This will save the exception in memory without persisting it to the DB
        // We want to wait for the result from test. So, we will only persist
        // the exception data in the database if the test did not succeed.
        $this->currentException = $exception;
    }

    public function setStatus(string $testStatus)
    {
        if ($this->shouldIgnore()) {
            return;
        }

        $status = Status::fromTestStatus($testStatus);

        $this->currentExampleBuilder->setStatus($testStatus, $status);

        if ($status !== Status::SUCCESS && $this->currentException !== null) {
            $this->currentExampleBuilder->setException(ExceptionInfo::make($this->currentException));
        }
    }

    public function build()
    {
        if ($this->shouldIgnore()) {
            return;
        }

        $example = $this->currentExampleBuilder->build();

        if ($example->getStatus() !== Status::SUCCESS) {
            $this->testRun->addFailedTestLink($example);
        }
    }

    public function shouldIgnore(): bool
    {
        return is_null($this->currentExampleBuilder);
    }

    private function getTitleFor(string $type, Collection $annotations, string $classOrMethodName)
    {
        return $annotations->get('title')
            ?: $annotations->get('testdox')
            ?: $this->settings->generateTitle($type, $classOrMethodName);
    }

    private function getStartLine($className, $methodName)
    {
        return (new ReflectionMethod($className, $methodName))->getStartLine();
    }

    private function getExampleGroup(string $className, Collection $classAnnotations): ExampleGroupBuilder
    {
        if (optional(static::$currentExampleGroupBuilder)->is($className)) {
            return static::$currentExampleGroupBuilder;
        }

        return static::$currentExampleGroupBuilder = $this->makeExampleGroup($className, $classAnnotations);
    }

    private function makeExampleGroup(string $className, Collection $classAnnotations): ExampleGroupBuilder
    {
        return $this->runBuilder->newExampleGroup()
            ->setClassName($className)
            ->setTitle($this->getTitleFor('class', $classAnnotations, $className))
            ->setDescription($classAnnotations->get('description'))
            ->setArea($this->settings->getAreaSlug($className))
            ->setSlug($this->settings->generateSlugFromClassName($className))
            ->setOrderNum($classAnnotations->get('enlighten')['order'] ?? self::LAST_ORDER_POSITION);
    }
}
