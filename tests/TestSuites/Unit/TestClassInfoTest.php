<?php

namespace Tests\TestSuites\Unit;

use Styde\Enlighten\TestClassInfo;
use Tests\TestCase;

class TestClassInfoTest extends TestCase
{
    /** @test */
    function it_gets_a_default_title()
    {
        $clasInfo = new TestClassInfo('ListUsersTest');

        $this->assertSame('List Users', $clasInfo->getTitle());

        $clasInfo = new TestClassInfo('ListTestsTest');

        $this->assertSame('List Tests', $clasInfo->getTitle());

        $clasInfo = new TestClassInfo('ShowUsers');

        $this->assertSame('Show Users', $clasInfo->getTitle());

        $clasInfo = new TestClassInfo('CreateTestTest');

        $this->assertSame('Create Test', $clasInfo->getTitle());
    }
}
