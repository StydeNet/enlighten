<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\ExampleGroup;
use Tests\TestCase;

class RunTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function gets_the_signature_of_a_run()
    {
        $run = $this->createRun('main', 'abcde', true);
        $this->assertSame('main * abcde', $run->signature);

        $run = $this->createRun('develop', 'fghij', false);
        $this->assertSame('develop fghij', $run->signature);
    }

    /** @test */
    function a_run_has_many_groups()
    {
        $run = $this->createRun();

        $this->assertInstanceOf(HasMany::class, $run->groups());
        $run->groups()->create($this->getExampleGroupAttributes());

        $this->assertInstanceOf(ExampleGroup::class, $run->groups->first());
    }
}
