<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Response;

class ExampleGenerator
{
    /**
     * @var string
     */
    protected $examplesDirectory;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Response
     */
    private $response;

    public function __construct($examplesDirectory)
    {
        // This needs to be dynamic (from config, for example)
        $this->examplesDirectory = $examplesDirectory;
    }

    public function generateExample(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        $test = $this->getTestInfo();

        Example::updateOrCreate([
            'class_name' => $test['class'],
            'method_name' => $test['function'],
        ], [
            // Test
            'title' => $this->getTitleFrom($test),
            'description' => $this->getDescriptionFrom($test),
            // Request
            'request_headers' => $this->exportRequestHeaders(),
            'request_method' => $this->request->method(),
            'request_path' => $this->request->path(),
            'request_query_parameters' => $this->exportQueryParameters(),
            'request_input' => $this->exportRequestInput(),
            // Route
            'route' => $this->request->route()->uri(),
            'route_parameters' => $this->exportRouteParameters(),
            // Response
            'response_status' => $this->response->getStatusCode(),
            'response_headers' => $this->exportResponseHeaders(),
            'response_body' => $this->exportResponseContent(),
            'response_template' => $this->exportResponseTemplate(),
        ]);
    }

    protected function getTestInfo(): array
    {
        return collect(debug_backtrace())->first(function($trace) {
            return Str::contains($trace['file'], '/phpunit/')
                && Str::endsWith($trace['file'], '/Framework/TestCase.php');
        });
    }

    protected function getTitleFrom($test): string
    {
        return $this->getAnnotationFromTestMethod($test, 'testdox')
            ?: ucfirst(str_replace('_', ' ', $test['function']));
    }

    protected function getDescriptionFrom($test): ?string
    {
        return $this->getAnnotationFromTestMethod($test, 'description');
    }

    protected function exportRequestHeaders(): array
    {
        return [
            'accept' => $this->request->headers->get('accept'),
            'accept-language' => $this->request->headers->get('accept-language'),
            'accept-charset' => $this->request->headers->get('accept-charset'),
        ];
    }

    protected function exportQueryParameters(): array
    {
        return $this->request->query();
    }

    protected function exportRequestInput(): array
    {
        return $this->request->input();
    }

    protected function exportResponseHeaders(): array
    {
        return $this->response->headers->all();
    }

    protected function exportResponseContent()
    {
        return $this->response->getContent();
    }

    // @TODO: revisit this.
    protected function exportResponseTemplate(): ?string
    {
        if ($this->response->original instanceof View) {
            return var_export(File::get($this->response->original->getPath()), true);
        }

        return null;
    }

    /**
     * Export all route parameters as keys and the parameter-where conditions as values.
     *
     * @return array
     */
    protected function exportRouteParameters(): array
    {
        return collect($this->request->route()->parameterNames())
            ->mapWithKeys(function ($parameter) {
                return [$parameter => '*'];
            })
            ->merge($this->request->route()->wheres)
            ->map(function ($pattern, $name) {
                return [
                    'name' => $name,
                    'pattern' => $pattern,
                    'optional' => $this->isRouteParameterOptional($name),
                ];
            })
            ->values()
            ->all();
    }

    protected function isRouteParameterOptional($parameter): bool
    {
        return (bool) preg_match("/\{{$parameter}\?\}/", $this->request->route()->uri());
    }

    protected function getAnnotationFromTestMethod($test, $annotation): ?string
    {
        $method = new ReflectionMethod($test['class'], $test['function']);

        preg_match_all("#@{$annotation} (.*?)\n#s", $method->getDocComment(), $annotations);

        if (empty ($annotations[1])) {
            return null;
        }

        return trim($annotations[1][0], '. ');
    }
}
