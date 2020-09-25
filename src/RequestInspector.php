<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;

class RequestInspector
{
    private RouteInspector $routeInspector;

    public function __construct(RouteInspector $routeInspector)
    {
        $this->routeInspector = $routeInspector;
    }

    public function getInfoFrom(Request $request)
    {
        return new RequestInfo(
            $request->method(),
            $request->path(),
            $this->getHeadersFrom($request),
            $this->getQueryParametersFrom($request),
            $this->getInputFrom($request),
            $this->routeInspector->getInfoFrom($request->route())
        );
    }

    // @TODO: allow users to allow or blocklist the request headers.
    protected function getHeadersFrom(Request $request): array
    {
        return [
            'accept' => $request->headers->get('accept'),
            'accept-language' => $request->headers->get('accept-language'),
            'accept-charset' => $request->headers->get('accept-charset'),
        ];
    }

    protected function getInputFrom(Request $request)
    {
        return $request->input();
    }

    // @TODO: allow users to allow or blocklist the request query parameters.
    protected function getQueryParametersFrom(Request $request): array
    {
        return $request->query();
    }

    // @TODO: allow users to allow or blocklist the request input.
    protected function exportRequestInput(Request $request): array
    {
        return $request->input();
    }
}
