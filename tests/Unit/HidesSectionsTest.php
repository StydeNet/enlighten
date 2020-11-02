<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Section;
use Tests\TestCase;

class HidesSectionsTest extends TestCase
{
    /** @test */
    function determines_if_a_section_should_be_hidden()
    {
        $this->assertFalse(Enlighten::hide(Section::QUERIES));
        $this->assertTrue(Enlighten::show(Section::QUERIES));

        $this->setConfig([
            'enlighten.hide' => [
                Section::QUERIES
            ]
        ]);

        $this->assertTrue(Enlighten::hide(Section::QUERIES));
        $this->assertFalse(Enlighten::show(Section::QUERIES));
    }
}
