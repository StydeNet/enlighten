<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Settings;
use Styde\Enlighten\Facades\VersionControl;
use Tests\TestCase;

class DisableEnlightenTest extends TestCase
{
    /** @test */
    function enlighten_can_be_enabled()
    {
        $this->setConfig([
            'enlighten.enabled' => true,
        ]);

        $this->assertTrue(Settings::isEnabled());
        $this->assertFalse(Settings::isDisabled());

        $this->setConfig([
            'enlighten.enabled' => false,
        ]);

        $this->assertFalse(Settings::isEnabled());
        $this->assertTrue(Settings::isDisabled());
    }

    /**
     * @test
     * @testWith ["main", true]
     *           ["develop", true]
     *           ["feature/test", false]
     *           ["issue/123", false]
     */
    function enlighten_can_be_enabled_on_specific_branches($branch, $expected)
    {
        $this->setConfig([
            'enlighten.enabled' => ['main', 'develop'],
        ]);

        VersionControl::shouldReceive('currentBranch')->andReturn($branch);
        $this->assertSame($expected, Settings::isEnabled());
        $this->assertSame(! $expected, Settings::isDisabled());
    }
}
