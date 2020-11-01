<?php

namespace Tests\Feature;

class ViewRunsTest extends TestCase
{
    /** @test */
    public function get_dashboard_view(): void
    {
        $this->createRun(['head' => 'abc123']);
        $response = $this->get(route('enlighten.run.index'));

        $response
            ->assertOk()
            ->assertViewIs('enlighten::run.index');
    }
}
