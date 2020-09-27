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
    private array $excludeHeaders;
    private array $overwriteHeaders;
    private array $overwriteQueryParameters;
    private array $excludeQueryParameters;
    private array $excludeInput;
    private array $overwriteInput;

    public function __construct(RouteInspector $routeInspector, array $config = [])
    {
        $this->routeInspector = $routeInspector;
        $this->excludeHeaders = $config['headers']['exclude'] ?? [];
        $this->overwriteHeaders = $config['headers']['overwrite'] ?? [];
        $this->excludeQueryParameters = $config['query']['exclude'] ?? [];
        $this->overwriteQueryParameters = $config['query']['overwrite'] ?? [];
        $this->excludeInput = $config['input']['exclude'] ?? [];
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
            $request->headers->all(), $this->excludeHeaders, $this->overwriteHeaders
        );
    }

    protected function getInputFrom(Request $request)
    {
        return $this->replaceValues(
            $request->input(), $this->excludeInput, $this->overwriteInput
        );
    }

    protected function getQueryParametersFrom(Request $request): array
    {
        return $this->replaceValues(
            $request->query(), $this->excludeQueryParameters, $this->overwriteQueryParameters
        );
    }
}
