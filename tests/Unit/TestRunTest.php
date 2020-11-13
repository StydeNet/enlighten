<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Contracts\VersionControl;
use Styde\Enlighten\ExampleGroupBuilder;
use Styde\Enlighten\Models\ExampleGroup;
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
    function can_only_create_one_instance_of_test_run()
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
        $this->createExampleGroup(TestRun::getInstance()->save(), 'Tests\Unit\TestClass');

        $this->assertSame(1, Run::count());
        $this->assertSame(1, ExampleGroup::count());

        TestRun::getInstance()->reset();

        $this->assertSame(1, Run::count());
        $this->assertSame(0, ExampleGroup::count());
    }

    /** @test */
    function a_test_run_can_only_be_reset_once()
    {
        TestRun::getInstance()->reset();

        $this->createExampleGroup(TestRun::getInstance()->save(), 'Tests\Unit\TestClass');

        // Does nothing because the test run was already reset before.
        TestRun::getInstance()->reset();

        $this->assertSame(1, Run::count());
        $this->assertSame(1, ExampleGroup::count());
    }

    /** @test */
    function can_get_info_from_a_custom_version_control_system()
    {
        $this->app->instance(VersionControl::class, new class implements VersionControl {
            public function currentBranch(): string
            {
                return 'my-branch';
            }

            public function head(): string
            {
                return 'abc123';
            }

            public function modified(): bool
            {
                return true;
            }
        });

        $run = TestRun::getInstance()->getRun();

        $this->assertSame('my-branch', $run->branch);
        $this->assertSame('abc123', $run->head);
        $this->assertTrue($run->modified);
    }
}
