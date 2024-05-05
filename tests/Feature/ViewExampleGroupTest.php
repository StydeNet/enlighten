<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

class ViewExampleGroupTest extends TestCase
{
    #[Test]
    public function shows_the_list_of_test_methods_in_the_group(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();
        $exampleGroup = $this->createExampleGroup($run, 'Tests\Api\CreateUserTest', 'Create User', 'User module API');

        $this->createExample($exampleGroup, 'another_test', 'passed', 'The Class Title');

        $this->createExample($exampleGroup, 'first_test', 'passed', 'My First Test');

        $this->createExample($exampleGroup, 'second_test', 'passed', 'My Second Test');

        $response = $this->get(route('enlighten.group.show', [
            'run' => $run->id,
            'group' => $exampleGroup
        ]));

        $response
            ->assertOk()
            ->assertViewIs('enlighten::group.show')
            ->assertSeeText('The Class Title')
            ->assertSeeText('User module API')
            ->assertSeeText('My First Test')
            ->assertSeeText('My Second Test');
    }
}
