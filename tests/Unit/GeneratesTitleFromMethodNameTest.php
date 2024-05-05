<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Facades\Settings;
use Tests\TestCase;

class GeneratesTitleFromMethodNameTest extends TestCase
{
    #[Test]
    function generates_a_title_from_test_method_names_in_camel_case_format()
    {
        $this->assertSame(
            'Generates title from camel case format',
            Settings::generateTitle('method', 'GeneratesTitleFromCamelCaseFormat')
        );
    }

    #[Test]
    function generates_a_title_from_test_method_names_in_snake_format()
    {
        $this->assertSame(
            'Generates title from snake format',
            Settings::generateTitle('method', 'generates_title_from_snake_format')
        );
    }

    #[Test]
    function generates_titles_without_including_the_test_prefix()
    {
        $this->assertSame(
            'It removes the test prefix',
            Settings::generateTitle('method', 'test_it_removes_the_test_prefix')
        );

        $this->assertSame(
            'Removes the test prefix',
            Settings::generateTitle('method', 'testRemovesTheTestPrefix')
        );
    }

    #[Test]
    function generates_title_with_a_custom_generator()
    {
        Settings::setCustomTitleGenerator(function ($type, $methodName) {
            $this->assertSame('method', $type);

            return strtoupper(str_replace('_', ' ', $methodName));
        });

        $this->assertSame(
            'IT REMOVES THE TEST PREFIX',
            Settings::generateTitle('method', 'it_removes_the_test_prefix')
        );
    }
}
