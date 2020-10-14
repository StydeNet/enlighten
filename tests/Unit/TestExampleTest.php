<?php

namespace Tests\Unit;

use Styde\Enlighten\TestExampleGroup;
use Styde\Enlighten\TestExample;
use Tests\TestCase;

class TestExampleTest extends TestCase
{
    /** @test */
    function generated_titles_do_not_include_the_test_prefix()
    {
        $testMethodInfo = new TestExample(
            new TestExampleGroup('TestClass'),
            'test_it_removes_the_test_prefix'
        );

        $this->assertSame('It removes the test prefix', $testMethodInfo->getTitle());
    }
}
