<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Settings;
use Styde\Enlighten\Facades\VersionControl;
use Tests\TestCase;

class DisableEnlightenTest extends TestCase
{
    /** @test */
    function enlighten_dashboard_can_be_enabled()
    {
        $this->setConfig([
            'enlighten.dashboard' => true,
        ]);

        $this->assertTrue(Settings::dashboardEnabled());

        $this->setConfig([
            'enlighten.dashboard' => false,
        ]);

        $this->assertFalse(Settings::dashboardEnabled());
    }
}
