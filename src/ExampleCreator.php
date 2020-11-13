<?php

namespace Styde\Enlighten;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Styde\Enlighten\Models\Status;
use Styde\Enlighten\Utils\Annotations;
use Throwable;

class ExampleCreator
{
    /**
     * @var ExampleGroupBuilder|null
     */
    protected static $currentTestClass = null;

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
     * @var array
     */
    protected $classOptions = [];

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
     * @var array
     */
    protected $ignore;

    public function __construct(TestRun $testRun, Annotations $annotations, Settings $settings, array $config)
    {
        $this->testRun = $testRun;
        $this->annotations = $annotations;
        $this->ignore = $config['ignore'];
        $this->settings = $settings;
    }

    public function getCurrentExample(): ?ExampleBuilder
    {
        if ($this->missingSetup) {
            TestRun::getInstance()->reportMissingSetup();
        }

        return $this->currentExample;
    }

    public function makeExample(string $className, string $methodName)
    {
        $this->missingSetup = false;
        $this->currentExample = null;
        $this->currentException = null;

        $testClassInfo = $this->getTestExampleGroup($className);

        $annotations = $this->annotations->getFromMethod($className, $methodName);

        if ($this->shouldIgnore($className, $methodName, $annotations->get('enlighten', []))) {
            return;
        }

        $this->currentExample = new ExampleBuilder($testClassInfo, $methodName, [
            'line'  => $this->getStartLine($className, $methodName),
            'title' => $this->getTitleFor('method', $annotations, $methodName),
            'slug'  => $this->settings->generateSlugFromMethodName($methodName),
            'description' => $annotations->get('description'),
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

        // This will save the exception in memory without persiting it to the DB
        // We want to wait for the result from test. So, we will only persist
        // the exception data in the database if the test did not succeed.
        $this->currentException = $exception;
    }

    public function saveStatus(string $testStatus)
    {
        if (is_null($this->currentExample)) {
            return;
        }

        $example = $this->currentExample->saveStatus(Status::fromTestStatus($testStatus), $testStatus);

        if ($example->status !== Status::SUCCESS && $this->currentException !== null) {
            $this->saveException();

            TestRun::getInstance()->saveFailedTestLink($example);
        }
    }

    private function saveException()
    {
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

    private function getTestExampleGroup($className): ExampleGroupBuilder
    {
        if (optional(static::$currentTestClass)->is($className)) {
            return static::$currentTestClass;
        }

        return static::$currentTestClass = $this->makeTestExampleGroup($className);
    }

    private function makeTestExampleGroup($className): ExampleGroupBuilder
    {
        $annotations = $this->annotations->getFromClass($className);

        $this->classOptions = $annotations->get('enlighten', []);

        return new ExampleGroupBuilder($this->testRun, $className, [
            'title' => $this->getTitleFor('class', $annotations, $className),
            'description' => $annotations->get('description'),
            'area' => $this->settings->getAreaSlug($className),
            'slug' => $this->settings->generateSlugFromClassName($className)
        ]);
    }

    private function shouldIgnore(string $className, string $methodName, array $options): bool
    {
        $options = array_merge($this->classOptions, $options);

        // If the test has been explicitly ignored via the
        // annotation options we need to ignore the test.
        if (Arr::get($options, 'ignore', false)) {
            return true;
        }

        // If the test has been explicitly included via the
        // annotation options we need to include the test.
        if (Arr::get($options, 'include', false)) {
            return false;
        }

        // Otherwise check the patterns we've got from the
        // config to check if the test should be ignored.
        return Str::is($this->ignore, $className) || Str::is($this->ignore, $methodName);
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
