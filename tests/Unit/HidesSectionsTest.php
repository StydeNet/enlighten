<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Facades\Settings;
use Styde\Enlighten\Section;
use Tests\TestCase;

class HidesSectionsTest extends TestCase
{
    #[Test]
    function determines_if_a_section_should_be_hidden()
    {
        $this->assertFalse(Settings::hide(Section::QUERIES));
        $this->assertTrue(Settings::show(Section::QUERIES));

        $this->setConfig([
            'enlighten.hide' => [
                Section::QUERIES
            ]
        ]);

        $this->assertTrue(Settings::hide(Section::QUERIES));
        $this->assertFalse(Settings::show(Section::QUERIES));
    }
}
