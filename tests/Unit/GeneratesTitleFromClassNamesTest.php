<?php

namespace Tests\Unit;

use Illuminate\Support\Str;
use Styde\Enlighten\Facades\Enlighten;
use Tests\TestCase;

class GeneratesTitleFromClassNamesTest extends TestCase
{
    /** @test */
    function generates_title_from_class_name()
    {
        $this->assertSame('List Users', Enlighten::generateTitleFromClassName('ListUsersTest'));

        $this->assertSame('List Tests', Enlighten::generateTitleFromClassName('ListTestsTest'));

        $this->assertSame('Show Users', Enlighten::generateTitleFromClassName('ShowUsers'));

        $this->assertSame('Create Test', Enlighten::generateTitleFromClassName('CreateTestTest'));
    }

    /** @test */
    function generates_title_from_class_name_with_a_custom_generator()
    {
        Enlighten::setCustomTitleGenerator(function ($className, $from) {
            $this->assertSame('class', $from);

            return Str::of($className)
                ->replaceMatches('@([A-Z])@', ' $1')
                ->upper()
                ->trim()
                ->__toString();
        });

        $this->assertSame(
            'CREATE USER TEST',
            Enlighten::generateTitleFromClassName('CreateUserTest')
        );
    }
}
