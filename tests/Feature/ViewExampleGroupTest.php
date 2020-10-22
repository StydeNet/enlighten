<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewExampleGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_code_example_view(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();
        $exampleGroup = $this->createExampleGroup($run);
        $example = $this->createExampleInGroup($exampleGroup);
        $this->createHttpData($example);

        $response = $this->get(route('enlighten.group.show', ['run' => $run->id, 'area' => 'api', 'group' => $exampleGroup]));

        $response->assertOk()
            ->assertViewIs('enlighten::group.show')
            ->assertViewHas('group', $exampleGroup)
            // Group
            ->assertSeeText('Creates a new user')
            ->assertSeeText('User module API')
            // Example
            ->assertSeeText('register new users in the system.')
            // headers
            ->assertSeeText('text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8')
            ->assertSeeText('ISO-8859-1,utf-8;q=0.7,*;q=0.7')
            ->assertSeeText('en-us,en;q=0.5')
            ->assertSeeText("Wed, 23 Sep 2020 09:53:15 GMT")
            ->assertSeeText("http://localhost")
            ->assertSeeText("text/html; charset=UTF-8")
            ->assertSeeText("no-cache, private");
    }

    /** @test */
    public function displays_full_json_input_on_the_request_info_section(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();
        $exampleGroup = $this->createExampleGroup($run);
        $example = $this->createExampleInGroup($exampleGroup);
        $this->createHttpData($example, [
            'request_input' => [
                'key_1' => 'value_1',
                'key_2' => [
                    'key_3' => [1, 3],
                ],
            ]
        ]);

        $response = $this->get(route('enlighten.group.show', ['run' => $run->id, 'area' => 'api', 'group' => $exampleGroup]));

        $response->assertOk()
            ->assertSeeText('key_1')
            ->assertSeeText('key_2');
    }
}
