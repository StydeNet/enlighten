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
    /**
     * @var ExampleGroupCreator|null
     */
    protected static $currentExampleGroup = null;

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
    protected $currentExample = null;

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

        return $this->currentExample;
    }

    public function makeExample(string $className, string $methodName)
    {
        $this->missingSetup = false;
        $this->currentExample = null;
        $this->currentException = null;

        $exampleGroupCreator = $this->getExampleGroup($className);

        $annotations = $this->annotations->getFromMethod($className, $methodName);

        if ($this->profile->shouldIgnore($className, $methodName, $annotations->get('enlighten'))) {
            return;
        }

        $this->currentExample = new ExampleBuilder($exampleGroupCreator, $methodName, [
            'line'  => $this->getStartLine($className, $methodName),
            'title' => $this->getTitleFor('method', $annotations, $methodName),
            'slug'  => $this->settings->generateSlugFromMethodName($methodName),
            'description' => $annotations->get('description'),
            'order_num' => $annotations->get('enlighten')['order'] ?? 9999,
        ]);
    }

    public function saveQuery(QueryExecuted $query)
    {
        if (is_null($this->currentExample)) {
            return;
        }

        $this->currentExample->saveQuery($query);
    }

    public function captureException(Throwable $exception)
    {
        if (is_null($this->currentExample)) {
            return;
        }

        // This will save the exception in memory without persisting it to the DB
        // We want to wait for the result from test. So, we will only persist
        // the exception data in the database if the test did not succeed.
        $this->currentException = $exception;
    }

    public function saveStatus(string $testStatus)
    {
        if (is_null($this->currentExample)) {
            return;
        }

        $example = $this->currentExample->saveStatus($testStatus, Status::fromTestStatus($testStatus));

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

        $this->currentExample->saveExceptionData(
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

    private function getExampleGroup($className): ExampleGroupCreator
    {
        if (optional(static::$currentExampleGroup)->is($className)) {
            return static::$currentExampleGroup;
        }

        return static::$currentExampleGroup = $this->makeExampleGroup($className);
    }

    private function makeExampleGroup($className): ExampleGroupCreator
    {
        $annotations = $this->annotations->getFromClass($className);

        $this->profile->setClassOptions($annotations->get('enlighten'));

        return new ExampleGroupCreator($this->testRun, $className, [
            'title' => $this->getTitleFor('class', $annotations, $className),
            'description' => $annotations->get('description'),
            'area' => $this->settings->getAreaSlug($className),
            'slug' => $this->settings->generateSlugFromClassName($className),
            'order_num' => $annotations->get('enlighten')['order'] ?? 9999,
        ]);
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
