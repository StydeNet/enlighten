<?php

namespace Tests\Unit;

use Styde\Enlighten\TestClassInfo;
use Styde\Enlighten\TestMethodInfo;
use Styde\Enlighten\TestRun;
use Styde\Enlighten\Utils\GitInfo;
use Tests\TestCase;

class TestMethodInfoTest extends TestCase
{
    /** @test */
    function generated_titles_do_not_include_the_test_prefix()
    {
        $testMethodInfo = new TestMethodInfo(
            new TestClassInfo('TestClass'),
            'test_it_removes_the_test_prefix'
        );

        $this->assertSame('It removes the test prefix', $testMethodInfo->getTitle());
    }
}
