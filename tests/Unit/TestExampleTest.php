<?php

namespace Tests\Unit;

use Styde\Enlighten\TestExampleGroup;
use Styde\Enlighten\TestExample;
use Tests\TestCase;

class TestExampleTest extends TestCase
{
    /** @test */
    function generates_a_readable_default_title_from_test_methods_with_camel_case_format()
    {
        $testMethodInfo = new TestExample(
            new TestExampleGroup('TestClass'),
            'GeneratesTitleFromCamelCaseFormat'
        );

        $this->assertSame('Generates title from camel case format', $testMethodInfo->getTitle());
    }

    /** @test */
    function generates_a_readable_default_title_from_test_methods_with_snake_format()
    {
        $testMethodInfo = new TestExample(
            new TestExampleGroup('TestClass'),
            'generates_title_from_snake_format'
        );

        $this->assertSame('Generates title from snake format', $testMethodInfo->getTitle());
    }

    /** @test */
    function generates_default_titles_without_including_the_test_prefix()
    {
        $testMethodInfo = new TestExample(
            new TestExampleGroup('TestClass'),
            'test_it_removes_the_test_prefix'
        );

        $this->assertSame('It removes the test prefix', $testMethodInfo->getTitle());

        $testMethodInfo = new TestExample(
            new TestExampleGroup('TestClass'),
            'testRemovesTheTestPrefix'
        );

        $this->assertSame('Removes the test prefix', $testMethodInfo->getTitle());
    }
}
