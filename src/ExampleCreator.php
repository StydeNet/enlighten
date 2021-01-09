<?php

namespace Styde\Enlighten;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Styde\Enlighten\Models\Status;
use Styde\Enlighten\Utils\Annotations;
use Throwable;

class ExampleCreator
{
    const LAST_ORDER_POSITION = 9999;
    /**
     * @var ExampleGroupBuilder|null
     */
    protected static $currentExampleGroupBuilder = null;

    /**
     * @var Throwable
     */
    protected $currentException;

    /**
     * @var bool
     */
    protected $missingSetup = true;

    /**
     * @var ExampleBuilder|null
     */
    protected $exampleBuilder = null;

    /**
     * @var TestRun
     */
    protected $testRun;

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

    public function __construct(TestRun $testRun, Annotations $annotations, Settings $settings, ExampleProfile $profile)
    {
        $this->testRun = $testRun;
        $this->annotations = $annotations;
        $this->settings = $settings;
        $this->profile = $profile;
    }

    public function getCurrentExample(): ?ExampleBuilder
    {
        if ($this->missingSetup) {
            $this->testRun->reportMissingSetup();
        }

        return $this->exampleBuilder;
    }

    public function makeExample(string $className, string $methodName)
    {
        $this->missingSetup = false;
        $this->exampleBuilder = null;
        $this->currentException = null;

        if (optional(static::$currentExampleGroupBuilder)->is($className)) {
            $exampleGroupBuilder = static::$currentExampleGroupBuilder;
        } else {
            $exampleGroupBuilder = static::$currentExampleGroupBuilder = new DatabaseExampleGroupBuilder;
        }

        $classAnnotations = $this->annotations->getFromClass($className);

        $exampleGroupBuilder
            ->setTestRun($this->testRun)
            ->setClassName($className)
            ->setTitle($this->getTitleFor('class', $classAnnotations, $className))
            ->setDescription($classAnnotations->get('description'))
            ->setArea($this->settings->getAreaSlug($className))
            ->setSlug($this->settings->generateSlugFromClassName($className))
            ->setOrderNum($classAnnotations->get('enlighten')['order'] ?? self::LAST_ORDER_POSITION);

        $annotations = $this->annotations->getFromMethod($className, $methodName);

        $options = array_merge($classAnnotations->get('enlighten', []), $annotations->get('enlighten', []));

        if ($this->profile->shouldIgnore($className, $methodName, $options)) {
            return;
        }

        $this->exampleBuilder = $this->newExampleBuilder();

        $this->exampleBuilder
            ->setMethodName($methodName)
            ->setExampleGroupCreator($exampleGroupBuilder)
            ->setSlug($this->settings->generateSlugFromMethodName($methodName))
            ->setTitle($this->getTitleFor('method', $annotations, $methodName))
            ->setDescription($annotations->get('description'))
            ->setLine($this->getStartLine($className, $methodName))
            ->setOrderNum($annotations->get('enlighten')['order'] ?? self::LAST_ORDER_POSITION);
    }

    private function newExampleBuilder()
    {
        return new DatabaseExampleBuilder();
    }

    public function saveQuery(QueryExecuted $query)
    {
        if (is_null($this->exampleBuilder)) {
            return;
        }

        $this->exampleBuilder->saveQuery($query);
    }

    public function captureException(Throwable $exception)
    {
        if (is_null($this->exampleBuilder)) {
            return;
        }

        // This will save the exception in memory without persisting it to the DB
        // We want to wait for the result from test. So, we will only persist
        // the exception data in the database if the test did not succeed.
        $this->currentException = $exception;
    }

    public function saveStatus(string $testStatus)
    {
        if (is_null($this->exampleBuilder)) {
            return;
        }

        $example = $this->exampleBuilder->saveStatus($testStatus, Status::fromTestStatus($testStatus));

        if ($example->status !== Status::SUCCESS) {
            $this->testRun->saveFailedTestLink($example);
            $this->saveException();
        }
    }

    private function saveException()
    {
        if ($this->currentException === null) {
            return;
        }

        $this->exampleBuilder->saveExceptionData(
            get_class($this->currentException),
            $this->currentException,
            $this->getExtraExceptionData($this->currentException)
        );
    }

    private function getExtraExceptionData(?Throwable $exception): array
    {
        if ($exception instanceof ValidationException) {
            return [
                'errors' => $exception->errors(),
            ];
        }

        return [];
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
}
