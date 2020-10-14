<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\TestRun;
use Tests\TestCase;

class TestRunTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        // Reset the instance to allow TestRun to be properly unit tested.
        // This is required in the tearDown() method to make sure this
        // test does not interfere with all the integration tests.
        TestRun::resetInstance();

        parent::tearDown();
    }

    /** @test */
    function can_only_create_an_instance_of_test_run()
    {
        $reflection = new \ReflectionClass(TestRun::class);

        $this->assertTrue($reflection->getConstructor()->isPrivate());
    }

    /** @test */
    function can_get_a_singleton_instance_of_test_run()
    {
        $this->assertInstanceOf(TestRun::class, TestRun::getInstance());

        $this->assertSame(TestRun::getInstance(), TestRun::getInstance());
    }

    /** @test */
    function can_reset_a_test_run()
    {
        TestRun::getInstance()->save();

        $this->assertNotNull(Run::first());

        TestRun::getInstance()->reset();

        $this->assertNull(Run::first());
    }

    /** @test */
    function a_test_run_can_only_be_reset_once()
    {
        TestRun::getInstance()->save();
        TestRun::getInstance()->reset();

        $this->assertNull(Run::first());

        TestRun::getInstance()->save();
        TestRun::getInstance()->reset();

        $this->assertNotNull(Run::first());
    }
}
