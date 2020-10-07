<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\GetsStatsFromGroups;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;
use Tests\TestCase;

class RunTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_run_has_many_groups()
    {
        $run = $this->createRun();

        $this->assertInstanceOf(HasMany::class, $run->groups());
        $run->groups()->create($this->getExampleGroupAttributes());

        $this->assertInstanceOf(ExampleGroup::class, $run->groups->first());
    }
}
