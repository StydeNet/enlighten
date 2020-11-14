<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\ExampleGroup;

class ExampleGroupCreator
{
    /**
     * @var TestRun
     */
    private $testRun;

    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var ExampleGroup|null
     */
    protected $exampleGroup = null;

    public function __construct(TestRun $testRun, string $className, array $attributes = [])
    {
        $this->testRun = $testRun;
        $this->className = $className;
        $this->attributes = $attributes;
    }

    public function is(string $name): bool
    {
        return $this->className === $name;
    }

    public function save(): ExampleGroup
    {
        if ($this->exampleGroup != null) {
            return $this->exampleGroup;
        }

        $run = $this->testRun->save();

        $this->exampleGroup = ExampleGroup::create(
            array_merge($this->attributes, [
                'run_id' => $run->id,
                'class_name' => $this->className,
            ])
        );

        return $this->exampleGroup;
    }
}
