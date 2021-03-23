<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\Contracts\ExampleBuilder;

abstract class BaseExampleBuilder implements ExampleBuilder
{
    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var array
     */
    protected $providedData;

    /**
     * @var string|null
     */
    protected $dataName;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var int
     */
    protected $line;

    /**
     * @var int
     */
    protected $order_num;

    /**
     * @var string|null
     */
    protected $testStatus;

    /**
     * @var string|null
     */
    protected $status;

    public function setTitle($title): ExampleBuilder
    {
        $this->title = $title;

        return $this;
    }

    public function setMethodName(string $methodName): ExampleBuilder
    {
        $this->methodName = $methodName;

        return $this;
    }

    public function setProvidedData(array $data = null): ExampleBuilder
    {
        $this->providedData = $data;

        return $this;
    }

    public function setDataName(string $name = null): ExampleBuilder
    {
        $this->dataName = $name;

        return $this;
    }

    public function setOrderNum(int $order_num): ExampleBuilder
    {
        $this->order_num = $order_num;

        return $this;
    }

    public function setDescription(?string $description): ExampleBuilder
    {
        $this->description = $description;

        return $this;
    }

    public function setSlug(string $slug): ExampleBuilder
    {
        $this->slug = $slug;

        return $this;
    }

    public function setStatus(string $testStatus, string $status)
    {
        $this->testStatus = $testStatus;
        $this->status = $status;
    }

    public function setLine(int $line): ExampleBuilder
    {
        $this->line = $line;

        return $this;
    }
}
