<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\ExampleGroupBuilder;

abstract class BaseExampleGroupBuilder implements ExampleGroupBuilder
{
    /**
     * @var string
     */
    protected $area;

    /**
     * @var int
     */
    protected $orderNum;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $slug;

    public function setArea(string $area): ExampleGroupBuilder
    {
        $this->area = $area;

        return $this;
    }

    public function setClassName(string $className): ExampleGroupBuilder
    {
        $this->className = $className;

        return $this;
    }

    public function is(string $name): bool
    {
        return $this->className === $name;
    }

    public function setOrderNum(int $orderNum): ExampleGroupBuilder
    {
        $this->orderNum = $orderNum;

        return $this;
    }

    public function setDescription(?string $description): ExampleGroupBuilder
    {
        $this->description = $description;

        return $this;
    }

    public function setSlug(string $slug): ExampleGroupBuilder
    {
        $this->slug = $slug;

        return $this;
    }

    public function setTitle(string $title): ExampleGroupBuilder
    {
        $this->title = $title;

        return $this;
    }
}
