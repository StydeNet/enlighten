<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ExampleGenerator
{
    protected $exclude;
    /**
     * @var TestInspector
     */
    private $testInspector;

    public function __construct(array $config, TestInspector $testInspector)
    {
        $this->exclude = $config['exclude'];
        $this->testInspector = $testInspector;
    }

    public function generateExample(Request $request, Response $response)
    {
        $test = $this->testInspector->getInfo();

        if ($test->isExcluded($this->exclude)) {
            return;
        }

        Example::updateOrCreate([
            'class_name' => $test->getClass(),
            'method_name' => $test->getMethod(),
        ], [
            // Test
            'title' => $test->getTitle(),
            'description' => $test->getDescription(),
            // Request
            'request_headers' => $this->exportRequestHeaders($request),
            'request_method' => $request->method(),
            'request_path' => $request->path(),
            'request_query_parameters' => $this->exportQueryParameters($request),
            'request_input' => $this->exportRequestInput($request),
            // Route
            'route' => $request->route()->uri(),
            'route_parameters' => $this->exportRouteParameters($request),
            // Response
            'response_status' => $response->getStatusCode(),
            'response_headers' => $this->exportResponseHeaders($response),
            'response_body' => $this->exportResponseContent($response),
            'response_template' => $this->exportResponseTemplate($response),
        ]);
    }

    // @TODO: allow users to allow or blocklist the request headers.
    protected function exportRequestHeaders(Request $request): array
    {
        return [
            'accept' => $request->headers->get('accept'),
            'accept-language' => $request->headers->get('accept-language'),
            'accept-charset' => $request->headers->get('accept-charset'),
        ];
    }

    // @TODO: allow users to allow or blocklist the request query parameters.
    protected function exportQueryParameters(Request $request): array
    {
        return $request->query();
    }

    // @TODO: allow users to allow or blocklist the request input.
    protected function exportRequestInput(Request $request): array
    {
        return $request->input();
    }

    // @TODO: allow users to allow or blocklist the response headers.
    protected function exportResponseHeaders(Response $response): array
    {
        return $response->headers->all();
    }

    protected function exportResponseContent(Response $response)
    {
        return $response->getContent();
    }

    // @TODO: revisit this.
    protected function exportResponseTemplate(Response $response): ?string
    {
        if ($response->original instanceof View) {
            return var_export(File::get($response->original->getPath()), true);
        }

        return null;
    }

    /**
     * Export all route parameters as keys and the parameter-where conditions as values.
     *
     * @return array
     */
    protected function exportRouteParameters(Request $request): array
    {
        return collect($request->route()->parameterNames())
            ->mapWithKeys(function ($parameter) {
                return [$parameter => '*'];
            })
            ->merge($request->route()->wheres)
            ->map(function ($pattern, $name) use ($request) {
                return [
                    'name' => $name,
                    'pattern' => $pattern,
                    'optional' => $this->isRouteParameterOptional($request, $name),
                ];
            })
            ->values()
            ->all();
    }

    protected function isRouteParameterOptional(Request $request, $parameter): bool
    {
        return (bool) preg_match("/{{$parameter}\?}/", $request->route()->uri());
    }
}
