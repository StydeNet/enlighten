<?php

namespace Tests\Feature;

class ListRunsTest extends TestCase
{
    /** @test */
    public function get_dashboard_view(): void
    {
        $this->withoutExceptionHandling();

        $this->createRun(['head' => '111111']);
        $this->createRun(['head' => '222222']);
        $this->createRun(['head' => '333333']);

        $response = $this->get(route('enlighten.run.index'));

        $response
            ->assertOk()
            ->assertViewIs('enlighten::dashboard.index')
            ->assertViewHas('runs');
    }
}
