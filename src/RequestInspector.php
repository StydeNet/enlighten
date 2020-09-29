<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;

class RequestInspector
{
    use ReplacesValues;

    private RouteInspector $routeInspector;
    /**
     * @var array|mixed
     */
    private array $ignoreHeaders;
    private array $overwriteHeaders;
    private array $overwriteQueryParameters;
    private array $ignoreQueryParameters;
    private array $ignoreInput;
    private array $overwriteInput;

    public function __construct(RouteInspector $routeInspector, array $config = [])
    {
        $this->routeInspector = $routeInspector;
        $this->ignoreHeaders = $config['headers']['ignore'] ?? [];
        $this->overwriteHeaders = $config['headers']['overwrite'] ?? [];
        $this->ignoreQueryParameters = $config['query']['ignore'] ?? [];
        $this->overwriteQueryParameters = $config['query']['overwrite'] ?? [];
        $this->ignoreInput = $config['input']['ignore'] ?? [];
        $this->overwriteInput = $config['input']['overwrite'] ?? [];
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

    protected function getHeadersFrom(Request $request): array
    {
        return $this->replaceValues(
            $request->headers->all(), $this->ignoreHeaders, $this->overwriteHeaders
        );
    }

    protected function getInputFrom(Request $request)
    {
        return $this->replaceValues(
            $request->post(), $this->ignoreInput, $this->overwriteInput
        );
    }

    protected function getQueryParametersFrom(Request $request): array
    {
        return $this->replaceValues(
            $request->query(), $this->ignoreQueryParameters, $this->overwriteQueryParameters
        );
    }
}
