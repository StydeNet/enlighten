<?php

namespace Tests\Unit;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Facades\Settings;
use Tests\TestCase;

class GeneratesTitleFromClassNameTest extends TestCase
{
    #[Test]
    function generates_title_from_class_name(): void
    {
        $this->assertSame('List Users', Settings::generateTitle('class', 'ListUsersTest'));

        $this->assertSame('List Tests', Settings::generateTitle('class', 'ListTestsTest'));

        $this->assertSame('Show Users', Settings::generateTitle('class', 'ShowUsers'));

        $this->assertSame('Create Test', Settings::generateTitle('class', 'CreateTestTest'));
    }

    #[Test]
    function generates_title_from_class_name_with_a_custom_generator(): void
    {
        Settings::setCustomTitleGenerator(function ($type, $className) {
            $this->assertSame('class', $type);

            return Str::of($className)
                ->replaceMatches('@([A-Z])@', ' $1')
                ->upper()
                ->trim()
                ->__toString();
        });

        $this->assertSame(
            'CREATE USER TEST',
            Settings::generateTitle('class', 'CreateUserTest')
        );
    }
}
