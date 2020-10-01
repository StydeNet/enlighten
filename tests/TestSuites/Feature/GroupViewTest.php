<?php

namespace Tests\TestSuites\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Example;
use Styde\Enlighten\ExampleGroup;
use Tests\TestCase;

class GroupViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_code_example_view(): void
    {
        $exampleGroup = $this->createExampleGroup();
        $this->createCodeExampleForGroup($exampleGroup);

        $response = $this->get(route('enlighten.group.show', ['suite' => 'api', 'group' => $exampleGroup]));

        $response->assertOk()
            ->assertViewIs('enlighten::group.show')
            ->assertViewHas('group', $exampleGroup)
            // Group
            ->assertSeeText('Creates a new user')
            ->assertSeeText('User module API')
            // Example
            ->assertSeeText('register new users in the system.')
            ->assertSeeText($this->responseBody())
            // headers
            ->assertSeeText('text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8')
            ->assertSeeText('ISO-8859-1,utf-8;q=0.7,*;q=0.7')
            ->assertSeeText('en-us,en;q=0.5')
            ->assertSeeText("Wed, 23 Sep 2020 09:53:15 GMT")
            ->assertSeeText("http://localhost")
            ->assertSeeText("text/html; charset=UTF-8")
            ->assertSeeText("no-cache, private");
    }

    protected function createExampleGroup()
    {
        return ExampleGroup::create([
            'class_name' => 'Tests\Feature\CreateUserTest',
            'title' => 'Create User',
            'description' => 'User module API'
        ]);
    }

    protected function createCodeExampleForGroup($group)
    {
        return Example::create([
            'title' => 'Creates a new user',
            'group_id' => $group->id,
            'method_name' => 'creates_a_new_user',
            'description' => 'register new users in the system.',
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
            'response_body' => $this->responseBody(),
            'response_template' => null
        ]);
    }

    protected function responseBody()
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
