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
    protected $customSlugGenerator = null;

    /**
     * @var Closure|null
     */
    protected $customTitleGenerator = null;

    public function hide(string $sectionName): bool
    {
        return in_array($sectionName, config('enlighten.hide', []));
    }

    public function show(string $sectionName): bool
    {
        return ! $this->hide($sectionName);
    }

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

        return $this->generateDefaultTitleFromMethodName($methodName);
    }

    protected function generateDefaultTitleFromMethodName($methodName): string
    {
        $result = Str::of($methodName);

        if ($result->startsWith('test')) {
            $result = $result->substr(4);
        }

        return $result
            ->replaceMatches('@([A-Z])|_@', ' $1')
            ->lower()
            ->trim()
            ->ucfirst()
            ->__toString();
    }

    public function generateTitleFromClassName($className): string
    {
        if ($this->customTitleGenerator) {
            return call_user_func($this->customTitleGenerator, $className, 'class');
        }

        return $this->generateDefaultTitleFromClassName($className);
    }

    protected function generateDefaultTitleFromClassName($className): string
    {
        $result = Str::of(class_basename($className));

        if ($result->endsWith('Test')) {
            $result = $result->substr(0, -4);
        }

        return $result
            ->replaceMatches('@([A-Z])@', ' $1')
            ->trim()
            ->__toString();
    }

    public function setCustomSlugGenerator(Closure $callback): self
    {
        $this->customSlugGenerator = $callback;

        return $this;
    }

    public function generateSlugFromClassName($className): string
    {
        if ($this->customSlugGenerator) {
            return call_user_func($this->customSlugGenerator, $className, 'class');
        }

        $result = Str::of($className);

        if ($result->startsWith('Tests\\')) {
            $result = $result->substr(6);
        }

        if ($result->endsWith('Test')) {
            $result = $result->substr(0, -4);
        }

        return $result
            ->replaceMatches('@([A-Z])@', '-$1')
            ->ltrim('-')
            ->slug()
            ->__toString();
    }
}
