<?php

namespace Tests\Unit;

use Styde\Enlighten\TestExampleGroup;
use Tests\TestCase;

class TestGroupExampleTest extends TestCase
{
    /** @test */
    function it_gets_a_default_title()
    {
        $clasInfo = new TestExampleGroup('ListUsersTest');
        $this->assertSame('List Users', $clasInfo->getTitle());

        $clasInfo = new TestExampleGroup('ListTestsTest');
        $this->assertSame('List Tests', $clasInfo->getTitle());

        $clasInfo = new TestExampleGroup('ShowUsers');
        $this->assertSame('Show Users', $clasInfo->getTitle());

        $clasInfo = new TestExampleGroup('CreateTestTest');
        $this->assertSame('Create Test', $clasInfo->getTitle());
    }
}
