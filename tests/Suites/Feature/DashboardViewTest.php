<?php

namespace Tests\Suites\Feature;

use Tests\TestCase;

class DashboardViewTest extends TestCase {

    /** @test */
    public function get_dashboard_view(): void
    {
        $response =$this->get(route('enlighten.dashboard'));

        $response->assertOk();
        $response->assertViewIs('enlighten::dashboard.index');
    }
}