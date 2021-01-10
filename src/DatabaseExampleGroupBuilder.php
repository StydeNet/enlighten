<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\ExampleGroup;

class DatabaseExampleGroupBuilder implements ExampleGroupBuilder
{
    /**
     * @var DatabaseRunBuilder
     */
    private $runBuilder;
    /**
     * @var TestRun
     */
    private $testRun;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $area;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var int
     */
    private $orderNum;

    /**
     * @var ExampleGroup|null
     */
    protected $exampleGroup = null;

    public function is(string $name): bool
    {
        return $this->className === $name;
    }

    public function save(): ExampleGroup
    {
        if ($this->exampleGroup != null) {
            return $this->exampleGroup;
        }

        $run = $this->runBuilder->save();

        $this->exampleGroup = ExampleGroup::create([
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

    public function setClassName(string $className): ExampleGroupBuilder
    {
        $this->className = $className;
        return $this;
    }

    public function setSlug(string $slug): ExampleGroupBuilder
    {
        $this->slug = $slug;
        return $this;
    }

    public function setArea(string $area): ExampleGroupBuilder
    {
        $this->area = $area;
        return $this;
    }

    public function setTitle(string $title): ExampleGroupBuilder
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(?string $description): ExampleGroupBuilder
    {
        $this->description = $description;
        return $this;
    }

    public function setOrderNum(int $orderNum): ExampleGroupBuilder
    {
        $this->orderNum = $orderNum;
        return $this;
    }

    public function newExample(): ExampleBuilder
    {
        return (new DatabaseExampleBuilder)->setExampleGroupBuilder($this);
    }

    public function setRunBuilder(DatabaseRunBuilder $runBuilder): ExampleGroupBuilder
    {
        $this->runBuilder = $runBuilder;

        return $this;
    }
}