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

        $this->generateExampleClass([
            // Test
            'className' => $this->generateClassNameFrom($test),
            'title' => $this->getTitleFrom($test),
            'description' => $this->getDescriptionFrom($test),
            // Request
            'requestHeaders' => $this->exportRequestHeaders(),
            'requestMethod' => $this->request->method(),
            'requestPath' => $this->request->path(),
            'requestQueryParameters' => $this->exportQueryParameters(),
            'requestInput' => $this->exportRequestInput(),
            // Route
            'route' => $this->request->route()->uri(),
            'routeParameters' => $this->exportRouteParameters(),
            // Response
            'responseStatus' => $this->response->getStatusCode(),
            'responseHeaders' => $this->exportResponseHeaders(),
            'responseBody' => $this->exportResponseContent(),
            'responseTemplate' => $this->exportResponseTemplate(),
        ]);
    }

    protected function generateExampleClass(array $attributes)
    {
        if (! File::isDirectory($this->examplesDirectory)) {
            File::makeDirectory($this->examplesDirectory);
            File::put($this->examplesDirectory.'/.gitignore', "*\n!.gitignore\n");
        }

        File::put(
            $this->getFileName($attributes['className']),
            $this->generateClassContent($attributes)
        );
    }

    protected function getFileName($className)
    {
        return $this->examplesDirectory.'/'.$className.'.php';
    }

    protected function generateClassContent(array $attributes): string
    {
        $stub = File::get(__DIR__ . '/../stubs/Example.php.stub');

        return str_replace($this->getPlaceholders($attributes), $attributes, $stub);
    }

    protected function getPlaceholders(array $attributes)
    {
        return collect($attributes)
            ->keys()
            ->map(function ($attribute) {
                return '{{'.$attribute.'}}';
            })
            ->all();
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

    protected function getDescriptionFrom($test)
    {
        return var_export($this->getAnnotationFromTestMethod($test, 'description'), true);
    }

    protected function generateClassNameFrom($test): string
    {
        return Str::studly($test['function']).'Example';
    }

    protected function exportRequestHeaders(): string
    {
        return var_export([
            'accept' => $this->request->headers->get('accept'),
            'accept-language' => $this->request->headers->get('accept-language'),
            'accept-charset' => $this->request->headers->get('accept-charset'),
        ], true);
    }

    protected function exportQueryParameters(): string
    {
        return var_export($this->request->query(), true);
    }

    protected function exportRequestInput(): string
    {
        return var_export($this->request->input(), true);
    }

    protected function exportResponseHeaders()
    {
        return var_export($this->response->headers->all(), true);
    }

    protected function exportResponseContent()
    {
        if (Str::contains($this->response->headers->get('content-type'), '/json')) {
            $contentAsArray = json_decode($this->response->getContent(), JSON_OBJECT_AS_ARRAY);
            return var_export($contentAsArray, true);
        }

        return var_export($this->response->getContent(), true);
    }

    // TODO: revisit this.
    protected function exportResponseTemplate(): string
    {
        if ($this->response->original instanceof View) {
            return var_export(File::get($this->response->original->getPath()), true);
        }

        return var_export(null, true);
    }

    /**
     * Export all route parameters as keys and the parameter-where conditions as values.
     *
     * @return string
     */
    protected function exportRouteParameters(): string
    {
        $parameters = collect($this->request->route()->parameterNames())
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

        return var_export($parameters, true);
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
