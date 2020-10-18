<?php

namespace Styde\Enlighten\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WidgetController
{
    public function __invoke(Request $request)
    {
        if (app()->bound($request->widget)) {
            return resolve($request->widget);
        }

        // Widget class
        $widgetClass = 'Styde\\Enlighten\\View\\Widgets\\'.Str::studly($request->widget);

        if(!Str::endsWith($widgetClass, 'Widget')) {
            $widgetClass .= 'Widget';
        }

        if (class_exists($widgetClass)) {
            return new $widgetClass;
        }

        // anonymous widget?
        $view = "enlighten::widgets.{$request->widget}";
        if (view()->exists($view)) {
            return view($view);
        }

        throw new Exception("The widget {$request->widget} does not exists.");
    }
}
