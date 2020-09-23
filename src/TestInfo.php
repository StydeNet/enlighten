<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use stdClass;

class TestInfo
{
    /**
     * @var stdClass
     */
    private $trace;
    /**
     * @var array
     */
    private $config;
    /**
     * @var array
     */
    private $texts;

    public function __construct(array $trace, array $config, array $texts = [])
    {
        $this->trace = $trace;
        $this->config = $config;
        $this->texts = $texts;
    }

    public function getClass()
    {
        return $this->trace['class'];
    }

    public function getMethod()
    {
        return $this->trace['function'];
    }

    public function getTitle(): string
    {
        return $this->texts['method_title'] ?? $this->getDefaultTitle();
    }

    protected function getDefaultTitle(): string
    {
        return ucfirst(str_replace('_', ' ', $this->getMethod()));
    }

    public function getDescription(): ?string
    {
        return $this->texts['method_description'] ?? null;
    }

    public function isExcluded($patterns)
    {
        if (Str::is($patterns, $this->trace['function']) || Str::is($patterns, $this->trace['class'])) {
            return true;
        }

        return Arr::get($this->config, 'exclude', false);
    }
}
