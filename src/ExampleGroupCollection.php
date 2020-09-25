<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ExampleGroupCollection extends EloquentCollection
{
    public function getTestSuites()
    {
        return $this->pluck('class_name')->map(function ($text) {
            return explode('\\', $text)[1];
        })->unique()->toArray();
    }
}
