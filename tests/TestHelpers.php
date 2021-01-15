<?php

namespace Tests;

use Illuminate\Contracts\Support\Arrayable;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\ExampleQuery;
use Styde\Enlighten\Models\ExampleRequest;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Models\Status;

trait TestHelpers
{
    protected function setConfig(array $config)
    {
        $this->afterApplicationCreated(function () use ($config) {
            $this->app->config->set($config);
        });
    }

    public function createRun($branch = 'main', $head = 'abcde', $modified = false): Run
    {
        if (is_array($branch)) {
            $attributes = $branch;

            $branch = $attributes['branch'] ?? 'main';
            $head = $attributes['head'] ?? 'abcde';
            $modified = $attributes['modified'] ?? false;
        }

        return Run::create([
            'branch' => $branch,
            'head' => $head,
            'modified' => $modified,
        ]);
    }

    protected function createExample(?ExampleGroup $group = null, string $methodName = 'test_method', string $testStatus = 'passed', string $title = 'Something does something'): Example
    {
        if (is_null($group)) {
            $group = $this->createExampleGroup();
        }

        return Example::create([
            'group_id' => $group->id,
            'method_name' => $methodName,
            'slug' => Enlighten::generateSlugFromMethodName($methodName),
            'test_status' => $testStatus,
            'status' => Status::fromTestStatus($testStatus),
            'title' => $title,
        ]);
    }

    protected function createExampleQuery(array $attributes = []) : ExampleQuery
    {
        return ExampleQuery::create(array_merge([
            'sql' => 'select * from users',
            'bindings' => [],
            'time' => '1.06'
        ], $attributes));
    }

    protected function createExampleGroup(?Run $run = null, $className = null, $title = null, $description = null): ExampleGroup
    {
        if (is_null($run)) {
            $run = $this->createRun();
        }

        return ExampleGroup::create($this->getExampleGroupAttributes([
            'run_id' => $run->id,
            'class_name' => $className,
            'title' => $title ?: ($className ? Enlighten::generateTitle('class', $className) : null),
            'description' => $description,
        ]));
    }

    protected function getExampleGroupAttributes(array $customAttributes = [])
    {
        if (!empty($customAttributes['class_name'])) {
            $className = $customAttributes['class_name'];
        } else {
            $className = 'Tests\Feature\CreateUserTest';
        }

        return array_merge([
            'class_name' => $className,
            'title' => 'Create User',
            'description' => 'User module API',
            'area' => Enlighten::getAreaSlug($className),
            'slug' => Enlighten::generateSlugFromClassName($className),
        ], array_filter($customAttributes));
    }

    protected function createExampleRequest($example, array $attributes = []): ExampleRequest
    {
        return $example->requests()->create($this->getExampleRequestAttributes($attributes));
    }

    protected function getExampleRequestAttributes(array $customAttributes = [])
    {
        return array_merge([
            'request_path' => 'user',
            'request_headers' => [
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'accept-charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                'accept-language' => 'en-us,en;q=0.5'
            ],
            'request_method' => 'POST',
            'request_query_parameters' => [],
            'request_input' => [
                'name' => 'Jeff',
                'email' => 'jeff@example.test',
                'password' => 'my-password'
            ],
            'request_files' => [],
            'route' => 'user',
            'route_parameters' => [],
            'response_status' => 302,
            'response_headers' => [
                'date' => [
                    'Wed, 23 Sep 2020 09:53:15 GMT'
                ],
                'location' => [
                    'http://localhost'
                ],
                'content-type' => [
                    'text/html; charset=UTF-8'
                ],
                'cache-control' => [
                    'no-cache, private'
                ]
            ],
            'response_body' => $this->redirectResponseBody(),
            'response_template' => null
        ], $customAttributes);
    }

    protected function redirectResponseBody()
    {
        return '<!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="refresh" content="0;url=\'http://localhost\'" />

                <title>Redirecting to http://localhost</title>
            </head>
            <body>
            Redirecting to <a href="http://localhost">http://localhost</a>.
            </body>
        </html>';
    }

    // Custom assertions

    public function assertArrayable($expected, $arrayable)
    {
        $this->assertInstanceOf(Arrayable::class, $arrayable);
        $this->assertSame($expected, $arrayable->toArray());
    }
}
