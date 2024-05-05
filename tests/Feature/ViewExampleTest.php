<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

class ViewExampleTest extends TestCase
{
    #[Test]
    public function shows_all_the_example_test_data(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();

        $exampleGroup = $this->createExampleGroup($run, 'Tests\TheClassName', 'The Class Title');

        $example = $this->createExample($exampleGroup, 'first_test', 'passed', 'My First Test');

        $response = $this->get($example->url);

        $response
            ->assertOk()
            ->assertViewIs('enlighten::example.show')
            ->assertSeeText('My First Test');
    }
}
