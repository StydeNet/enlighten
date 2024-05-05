<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

class ViewRunsTest extends TestCase
{
    #[Test]
    public function get_dashboard_view(): void
    {
        $this->createRun(['head' => 'abc123']);
        $response = $this->get(route('enlighten.run.index'));

        $response
            ->assertOk()
            ->assertViewIs('enlighten::run.index');
    }
}
