<?php

namespace Tests\Unit;

use Styde\Enlighten\Utils\GitInfo;
use Styde\Enlighten\TestClassInfo;
use Styde\Enlighten\TestRun;
use Tests\TestCase;

class TestClassInfoTest extends TestCase
{
    /** @test */
    function it_gets_a_default_title()
    {
        $testRun = new TestRun(new GitInfo);

        $clasInfo = new TestClassInfo($testRun, 'ListUsersTest');

        $this->assertSame('List Users', $clasInfo->getTitle());

        $clasInfo = new TestClassInfo($testRun, 'ListTestsTest');

        $this->assertSame('List Tests', $clasInfo->getTitle());

        $clasInfo = new TestClassInfo($testRun, 'ShowUsers');

        $this->assertSame('Show Users', $clasInfo->getTitle());

        $clasInfo = new TestClassInfo($testRun, 'CreateTestTest');

        $this->assertSame('Create Test', $clasInfo->getTitle());
    }
}
