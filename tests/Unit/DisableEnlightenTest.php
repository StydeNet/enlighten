<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Enlighten;
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

        $this->assertTrue(Enlighten::isEnabled());
        $this->assertFalse(Enlighten::isDisabled());

        $this->setConfig([
            'enlighten.enabled' => false,
        ]);

        $this->assertFalse(Enlighten::isEnabled());
        $this->assertTrue(Enlighten::isDisabled());
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
        $this->assertSame($expected, Enlighten::isEnabled());
        $this->assertSame(! $expected, Enlighten::isDisabled());
    }
}
