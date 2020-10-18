<?php

namespace Tests\Feature\Widgets;

use Tests\Feature\TestCase;

class RunsTableWidgetTest extends TestCase
{
    /** @test */
    public function get_all_the_runs_registered(): void
    {
        $this->createRun(['head' => '111111']);
        $this->createRun(['head' => '222222']);
        $this->createRun(['head' => '333333']);

        $response = $this->get(route('enlighten.widget', ['widget' => 'runs-table']));

        $response
            ->assertOk()
            ->assertHeader('content-type', 'text/html; charset=UTF-8')
            ->assertViewIs('enlighten::widgets.runs-table')
            ->assertViewHas('runs')
            ->assertSee('111111')
            ->assertSee('222222')
            ->assertSee('333333');
    }
}
