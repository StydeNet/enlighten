<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Enlighten;
use Tests\TestCase;

class GeneratesTitleFromMethodNameTest extends TestCase
{
    /** @test */
    function generates_a_title_from_test_method_names_in_camel_case_format()
    {
        $this->assertSame(
            'Generates title from camel case format',
            Enlighten::generateTitle('method', 'GeneratesTitleFromCamelCaseFormat')
        );
    }

    /** @test */
    function generates_a_title_from_test_method_names_in_snake_format()
    {
        $this->assertSame(
            'Generates title from snake format',
            Enlighten::generateTitle('method', 'generates_title_from_snake_format')
        );
    }

    /** @test */
    function generates_titles_without_including_the_test_prefix()
    {
        $this->assertSame(
            'It removes the test prefix',
            Enlighten::generateTitle('method', 'test_it_removes_the_test_prefix')
        );

        $this->assertSame(
            'Removes the test prefix',
            Enlighten::generateTitle('method', 'testRemovesTheTestPrefix')
        );
    }

    /** @test */
    function generates_title_with_a_custom_generator()
    {
        Enlighten::setCustomTitleGenerator(function ($type, $methodName) {
            $this->assertSame('method', $type);

            return strtoupper(str_replace('_', ' ', $methodName));
        });

        $this->assertSame(
            'IT REMOVES THE TEST PREFIX',
            Enlighten::generateTitle('method', 'it_removes_the_test_prefix')
        );
    }
}
