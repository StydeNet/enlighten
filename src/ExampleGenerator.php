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
    protected $exclude;

    public function __construct(array $config)
    {
        $this->exclude = $config['exclude'];
    }

    public function generateExample(Request $request, Response $response)
    {
        $test = $this->getTestInfo();

        if ($this->isTestExcluded($test)) {
            return;
        }

        Example::updateOrCreate([
            'class_name' => $test['class'],
            'method_name' => $test['function'],
        ], [
            // Test
            'title' => $this->getTitleFrom($test),
            'description' => $this->getDescriptionFrom($test),
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

    protected function getAnnotationFromTestMethod($test, $annotation): ?string
    {
        preg_match_all("#@{$annotation} (.*?)\n#s", $this->getTestMethodDocBlock($test), $annotations);

        if (empty ($annotations[1])) {
            return null;
        }

        return trim($annotations[1][0], '. ');
    }

    protected function isTestExcluded(array $test)
    {
        if (Str::is($this->exclude, $test['function']) || Str::is($this->exclude, $test['class'])) {
            return true;
        }
        
        $config = array_merge(
            $this->getTestClassConfig($test),
            $this->getTestMethodConfig($test)
        );

        if (isset ($config['exclude']) && $config['exclude']) {
            return true;
        }

        return false;
    }

    protected function getTestClassConfig(array $test): array
    {
        $classConfig = $this->getAnnotationFromTestClass($test, 'enlighten');

        if (is_null($classConfig)) {
            return [];
        }

        return json_decode($classConfig, JSON_OBJECT_AS_ARRAY);
    }

    protected function getAnnotationFromTestClass($test, $annotation): ?string
    {
        preg_match_all("#@{$annotation} (.*?)\n#s", $this->getTestClassDocBlock($test), $annotations);

        if (empty ($annotations[1])) {
            return null;
        }

        return trim($annotations[1][0], '. ');
    }

    protected function getTestClassDocBlock($test)
    {
        $class = new \ReflectionClass($test['class']);

        return $class->getDocComment();
    }

    protected function getTestMethodDocBlock($test)
    {
        $method = new ReflectionMethod($test['class'], $test['function']);

        return $method->getDocComment();
    }

    protected function getTestMethodConfig(array $test): array
    {
        $methodConfig = $this->getAnnotationFromTestMethod($test, 'enlighten');

        if (is_null($methodConfig)) {
            return [];
        }

        return json_decode($methodConfig, JSON_OBJECT_AS_ARRAY);
    }
}
