<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Facades\Settings;
use Tests\TestCase;

class DisableEnlightenTest extends TestCase
{
    #[Test]
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
