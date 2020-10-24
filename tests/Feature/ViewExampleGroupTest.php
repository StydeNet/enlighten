<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewExampleGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_the_list_of_test_methods_in_the_group(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();
        $exampleGroup = $this->createExampleGroup($run, 'Tests\Api\CreateUserTest', 'Create User', 'User module API');

        $this->createExampleTest([
            'group_id' => $exampleGroup->id,
            'method_name' => 'another_test',
            'title' => 'The Class Title'
        ]);


        $this->createExampleTest([
            'group_id' => $exampleGroup->id,
            'method_name' => 'first_test',
            'title' => 'My First Test'
        ]);

        $this->createExampleTest([
            'group_id' => $exampleGroup->id,
            'method_name' => 'second_test',
            'title' => 'My Second Test'
        ]);

        $response = $this->get(route('enlighten.group.show', ['run' => $run->id, 'area' => 'api', 'group' => $exampleGroup]));

        $response
            ->assertOk()
            ->assertViewIs('enlighten::group.show')
            ->assertSeeText('The Class Title')
            ->assertSeeText('My First Test')
            ->assertSeeText('My Second Test');
    }
}
