<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\ExampleRequest;
use Styde\Enlighten\Models\Run;

class StringResponseTest extends TestCase
{
    #[Test]
    function a_response_can_return_a_string(): void
    {
        $this->get('api/text')
            ->assertOk()
            ->assertSeeText('This is just a string');

        $run = Run::first();

        $this->assertNotNull($run, 'A Run record was not created in the database.');

        tap($group = $run->groups()->first(), function (ExampleGroup $exampleGroup) {
            $this->assertSame('Tests\Integration\StringResponseTest', $exampleGroup->class_name);
        });

        tap($group->examples()->first(), function (Example $example) use ($group) {
            $this->assertTrue($example->group->is($group));
            $this->assertSame('a_response_can_return_a_string', $example->method_name);

            tap($example->requests->first(), function ($request) {
                $this->assertNotNull($request);

                $this->assertSame('GET', $request->request_method);
                $this->assertSame('api/text', $request->request_path);

                $this->assertSame('This is just a string', $request->response_body);
            });
        });
    }

    #[Test]
    function a_json_response_can_be_a_string(): void
    {
        $this->get('api/json-string')
            ->assertOk()
            ->assertHeader('content-type', 'application/json')
            ->assertSeeText('Unsubscription was successful');

        $example = ExampleRequest::first();

        $this->assertSame('Unsubscription was successful', $example->response_body);
    }
}
