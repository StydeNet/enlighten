<?php

namespace Tests\Unit;

use ReflectionClass;
use Styde\Enlighten\TestClassInfo;
use Styde\Enlighten\TestRun;
use Tests\TestCase;

class TestRunTest extends TestCase
{
    /** @test */
    function can_only_create_one_instance_of_test_run()
    {
        $reflection = new ReflectionClass(TestRun::class);

        $this->assertTrue($reflection->getConstructor()->isPrivate());
    }

    /** @test */
    function can_get_the_singleton_instance_of_the_test_run()
    {
        $this->assertInstanceOf(TestRun::class, TestRun::getInstance());
        $this->assertSame(TestRun::getInstance(), TestRun::getInstance());
    }

    /** @test */
    function can_get_a_class_added_to_the_test_run()
    {
        $testRun = TestRun::getInstance();

        $this->assertFalse($testRun->has('Test'));

        $testRun->add('Test', $classInfo = new TestClassInfo('Test'));

        $this->assertTrue($testRun->has('Test'));

        $this->assertSame($classInfo, $testRun->get('Test'));
    }
}
