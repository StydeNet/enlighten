<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;

class RequestInspector
{
    public function getDataFrom(Request $request)
    {
        return new RequestInfo(
            $request->method(),
            $request->path(),
            $request->headers->all(),
            $request->query(),
            $request->post(),
        );
    }
}
