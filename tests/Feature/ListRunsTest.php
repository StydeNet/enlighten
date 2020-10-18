<?php

namespace Tests\Feature;

class ListRunsTest extends TestCase
{
    /** @test */
    public function get_dashboard_view(): void
    {
        $response = $this->get(route('enlighten.run.index'));

        $response->assertOk()
            ->assertViewIs('enlighten::dashboard.index');
    }
}
