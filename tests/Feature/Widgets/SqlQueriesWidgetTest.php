<?php

namespace Tests\Feature\Widgets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\TestCase;

class SqlQueriesWidgetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_the_list_of_queries_for_a_given_request(): void
    {
        $exampleTest = $this->createExampleTest([
            'group_id' => $this->createExampleGroup()->id
        ]);

        $this->createExampleQuery([
            'sql' => "select * from users wehre id = ?",
            'bindings' => [123],
            'example_id' => $exampleTest->id,
        ]);

        $this->createExampleQuery([
            'sql' => "select * from users wehre name LIKE ?",
            'bindings' => ['joe'],
            'example_id' => $exampleTest->id
        ]);

        $response = $this->get(route('enlighten.widget', ['widget' => 'sql-queries', 'example' => $exampleTest->id]));

        $response->assertOk()
            ->assertViewIs('enlighten::widgets.sql-queries')
            ->assertSee("select * from users wehre id = ?")
            ->assertSee("select * from users wehre name LIKE ?");
    }

    /** @test */
    public function return_empty_response_if_no_queries_have_been_recorded(): void
    {
        $exampleTest = $this->createExampleTest([
            'group_id' => $this->createExampleGroup()->id
        ]);

        $response = $this->get(route('enlighten.widget', ['widget' => 'sql-queries', 'example' => $exampleTest->id]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
