<?php

namespace Tests;

use Styde\Enlighten\Example;
use Styde\Enlighten\ExampleGroup;

trait TestHelpers
{
    protected function setConfig(array $config)
    {
        $this->app->config->set($config);
    }

    protected function createExampleGroup(string $name = null): ExampleGroup
    {
        return ExampleGroup::create([
            'class_name' => $name ?: 'Tests\Feature\CreateUserTest',
            'title' => $name ?: 'Create User',
            'description' => 'User module API'
        ]);
    }

    protected function createExampleInGroup(ExampleGroup $group): Example
    {
        return Example::create([
            'title' => 'Creates a new user',
            'group_id' => $group->id,
            'method_name' => 'creates_a_new_user',
            'description' => 'register new users in the system.',
        ]);
    }

    protected function createHttpData(Example $example)
    {
        $example->http_data->fill($this->getHttpDataAttributes())->save();

        return $example->http_data;
    }

    protected function getHttpDataAttributes()
    {
        return [
            'request_path' => 'user',
            'request_headers' => [
                "accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "accept-charset" => "ISO-8859-1,utf-8;q=0.7,*;q=0.7",
                "accept-language" => "en-us,en;q=0.5"
            ],
            'request_method' => 'POST',
            'request_query_parameters' => [],
            'request_input' => [
                "name" => "Jeff",
                "email" => "jeff@example.test",
                "password" => "my-password"
            ],
            'route' => 'user',
            'route_parameters' => [],
            'response_status' => 302,
            'response_headers' => [
                "date" => [
                    "Wed, 23 Sep 2020 09:53:15 GMT"
                ],
                "location" => [
                    "http://localhost"
                ],
                "content-type" => [
                    "text/html; charset=UTF-8"
                ],
                "cache-control" => [
                    "no-cache, private"
                ]
            ],
            'response_body' => $this->redirectResponseBody(),
            'response_template' => null
        ];
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
}
