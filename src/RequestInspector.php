<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;

class RequestInspector
{
    use ReplacesValues;

    private RouteInspector $routeInspector;

    public function __construct(RouteInspector $routeInspector)
    {
        $this->routeInspector = $routeInspector;
    }

    public function getDataFrom(Request $request)
    {
        return new RequestInfo(
            $request->method(),
            $request->path(),
            $request->headers->all(),
            $request->query(),
            $request->post(),
            $this->routeInspector->getInfoFrom($request->route())
        );
    }
}
