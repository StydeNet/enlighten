<?php


namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Contracts\VersionControl;
use Styde\Enlighten\Drivers\DatabaseRunBuilder;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;
use Tests\TestCase;

class DatabaseRunBuilderTest extends TestCase
{
    #[Test]
    function can_reset_a_test_run(): void
    {
        $testRunBuilder = $this->app->make(DatabaseRunBuilder::class);

        $this->createExampleGroup($testRunBuilder->save(), 'Tests\Unit\TestClass');

        $this->assertSame(1, Run::count());
        $this->assertSame(1, ExampleGroup::count());

        $testRunBuilder->reset();

        $this->assertSame(1, Run::count());
        $this->assertSame(0, ExampleGroup::count());
    }

    #[Test]
    function can_get_info_from_a_custom_version_control_system(): void
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

        $run = $this->app->make(DatabaseRunBuilder::class)->save();

        $this->assertSame('my-branch', $run->branch);
        $this->assertSame('abc123', $run->head);
        $this->assertTrue($run->modified);
    }
}
