<?php

namespace Styde\Enlighten;

use Closure;
use Illuminate\Support\Str;

class EnlightenSettings
{
    private ?Closure $customAreaResolver = null;

    public function setCustomAreaResolver(Closure $callback)
    {
        $this->customAreaResolver = $callback;

        return $this;
    }

    public function getAreaSlug(string $className)
    {
        return $this->getDefaultAreaSlug($className);
    }

    private function getDefaultAreaSlug($className): string
    {
        if ($this->customAreaResolver != null) {
            $area = call_user_func($this->customAreaResolver, $className);
        } else {
            $area = collect(explode('\\', $className))[1];
        }

        return Str::slug($area);
    }
}
