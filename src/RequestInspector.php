<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;

class RequestInspector
{
    private RouteInspector $routeInspector;

    private Collection $excludeHeaders;
    private Collection $overwriteHeaders;

    public function __construct(RouteInspector $routeInspector, array $config = [])
    {
        $this->routeInspector = $routeInspector;
        $this->excludeHeaders = Collection::make($config['headers']['exclude'] ?? []);
        $this->overwriteHeaders = Collection::make($config['headers']['overwrite'] ?? []);
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
        return Collection::make($request->headers->all())
            ->exclude($this->excludeHeaders)
            ->overwrite($this->overwriteHeaders)
            ->all();
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
