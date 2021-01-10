<?php

namespace Tests\Unit;

use Tests\TestCase;
use Styde\Enlighten\TestRun;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestRunTest extends TestCase
{
    use RefreshDatabase;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

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
}
