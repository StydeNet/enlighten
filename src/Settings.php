<?php

namespace Styde\Enlighten;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Styde\Enlighten\Contracts\RunBuilder;
use Styde\Enlighten\Drivers\ApiRunBuilder;
use Styde\Enlighten\Drivers\DatabaseRunBuilder;
use Styde\Enlighten\Exceptions\InvalidDriverException;
use Styde\Enlighten\Facades\VersionControl;

class Settings
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

    public function isEnabled(): bool
    {
        $config = Config::get('enlighten.enabled');

        // You can enable Enlighten only on specific branches
        // of your control system (i.e.: main and develop)
        // and it'll be disabled in the other branches.
        if (is_array($config)) {
            return in_array(VersionControl::currentBranch(), $config);
        }

        return (bool) $config;
    }

    public function isDisabled(): bool
    {
        return ! $this->isEnabled();
    }

    public function startRecording()
    {
    }

    public function isRecording()
    {
        if (!file_exists('.enlighten')) {
            return false;
        }

        $config = file_get_contents('.enlighten');

        dd($config);
    }

    public function getDriver(): RunBuilder
    {
        switch (Config::get('enlighten.driver', 'database')) {
            case 'database':
                return new DatabaseRunBuilder;
            case 'api':
                return new ApiRunBuilder;
            default:
                throw new InvalidDriverException;
        }
    }

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

    public function generateTitle(string $type, string $classOrMethodName): string
    {
        if ($this->customTitleGenerator) {
            return call_user_func($this->customTitleGenerator, $type, $classOrMethodName);
        } elseif ($type == 'class') {
            return $this->generateDefaultTitleFromClassName($classOrMethodName);
        } else {
            return $this->generateDefaultTitleFromMethodName($classOrMethodName);
        }
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

    public function generateSlugFromMethodName($methodName): string
    {
        if ($this->customSlugGenerator) {
            return call_user_func($this->customSlugGenerator, $methodName, 'method');
        }

        $result = Str::of($methodName);

        if ($result->startsWith('test') || $result->startsWith('Test')) {
            $result = $result->substr(4);
        }

        return $result
            ->replaceMatches('@([A-Z])@', '-$1')
            ->ltrim('-')
            ->slug()
            ->__toString();
    }
}
