<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\Contracts\ExampleBuilder;
use Styde\Enlighten\Models\ExampleGroup;

class DatabaseExampleGroupBuilder extends BaseExampleGroupBuilder
{
    /**
     * @var DatabaseRunBuilder
     */
    private $runBuilder;

    /**
     * @var ExampleGroup|null
     */
    protected $exampleGroup = null;

    public function __construct(DatabaseRunBuilder $runBuilder)
    {
        $this->runBuilder = $runBuilder;
    }

    public function save(): ExampleGroup
    {
        if ($this->exampleGroup !== null) {
            return $this->exampleGroup;
        }

        $run = $this->runBuilder->save();

        $this->exampleGroup = ExampleGroup::updateOrCreate([
            'run_id' => $run->id,
            'class_name' => $this->className,
            'title' => $this->title,
            'description' => $this->description,
            'area' => $this->area,
            'slug' => $this->slug,
            'order_num' => $this->orderNum,
        ]);

        return $this->exampleGroup;
    }

    public function newExample(): ExampleBuilder
    {
        return new DatabaseExampleBuilder($this);
    }
}
