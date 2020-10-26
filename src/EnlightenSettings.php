<?php

namespace Styde\Enlighten;

use Closure;
use Illuminate\Support\Str;

class EnlightenSettings
{
    /**
     * @var Closure|null
     */
    private $customAreaResolver = null;

    /**
     * @var Closure|null
     */
    protected $customTitleGenerator = null;

    public function setCustomAreaResolver(Closure $callback): self
    {
        $this->customAreaResolver = $callback;

        return $this;
    }

    public function getAreaSlug(string $className): string
    {
        if ($this->customAreaResolver != null) {
            return Str::slug(call_user_func($this->customAreaResolver, $className));
        }

        return Str::slug(collect(explode('\\', $className))[1]);
    }

    public function setCustomTitleGenerator(Closure $callback): self
    {
        $this->customTitleGenerator = $callback;

        return $this;
    }

    public function generateTitleFromMethodName($methodName): string
    {
        if ($this->customTitleGenerator) {
            return call_user_func($this->customTitleGenerator, $methodName, 'method');
        }

        $result = Str::of($methodName);

        if ($result->startsWith('test')) {
            $result = $result->substr(4);
        }

        return (string)$result
            ->replaceMatches('@([A-Z])|_@', ' $1')
            ->lower()
            ->trim()
            ->ucfirst();
    }

    public function generateTitleFromClassName($className): string
    {
        if ($this->customTitleGenerator) {
            return call_user_func($this->customTitleGenerator, $className, 'class');
        }

        $result = Str::of(class_basename($className));

        if ($result->endsWith('Test')) {
            $result = $result->substr(0, -4);
        }

        return (string) $result
            ->replaceMatches('@([A-Z])@', ' $1')
            ->trim();
    }
}
