<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Enlighten;
use Tests\TestCase;

class GeneratesTitlesFromMethodNamesTest extends TestCase
{
    /** @test */
    function generates_a_title_from_test_method_names_in_camel_case_format()
    {
        $this->assertSame(
            'Generates title from camel case format',
            Enlighten::generateTitleFromMethodName('GeneratesTitleFromCamelCaseFormat')
        );
    }

    /** @test */
    function generates_a_title_from_test_method_names_in_snake_format()
    {
        $this->assertSame(
            'Generates title from snake format',
            Enlighten::generateTitleFromMethodName('generates_title_from_snake_format')
        );
    }

    /** @test */
    function generates_titles_without_including_the_test_prefix()
    {
        $this->assertSame(
            'It removes the test prefix',
            Enlighten::generateTitleFromMethodName('test_it_removes_the_test_prefix')
        );

        $this->assertSame(
            'Removes the test prefix',
            Enlighten::generateTitleFromMethodName('testRemovesTheTestPrefix')
        );
    }

    /** @test */
    function generates_title_with_a_custom_generator()
    {
        Enlighten::setCustomTitleGenerator(function ($methodName, $from) {
            $this->assertSame('method', $from);

            return strtoupper(str_replace('_', ' ', $methodName));
        });

        $this->assertSame(
            'IT REMOVES THE TEST PREFIX',
            Enlighten::generateTitleFromMethodName('it_removes_the_test_prefix')
        );
    }
}
