<?php

namespace Tests\Feature;

use Styde\Enlighten\Models\ExampleGroup;
use Tests\TestCase;

class DashboardViewTest extends TestCase {

    /** @test */
    public function get_dashboard_view(): void
    {
        $this->withoutExceptionHandling();

        $this->createExampleGroup($this->createRun(), 'Tests\Api\UserTest', 'User tests');

        $response = $this->get(route('enlighten.dashboard'));

        $response
            ->assertOk()
            ->assertViewIs('enlighten::dashboard.index');
    }

    /** @test */
    public function redirect_to_intro_page_if_no_data_has_been_recorded_yet(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('enlighten.dashboard'));

        $response->assertRedirect(route('enlighten.intro'));
    }

    /** @test */
    public function get_test_groups_by_test_suite(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\UserTest', 'User tests');
        $this->createExampleGroup($run, 'Tests\Api\PostTest', 'Post tests');
        $this->createExampleGroup($run, 'Tests\Feature\UserTest', 'Users Feature tests');
        $this->createExampleGroup($run, 'Tests\Unit\FilterTest', 'Filter tests');

        $response = $this->get(route('enlighten.dashboard', ['suite' => 'api']));

        $response->assertOk()
            ->assertViewHas('active')
            ->assertSeeText('User tests')
            ->assertSeeText('Post tests')
            ->assertDontSeeText('Users Feature tests')
            ->assertDontSeeText('Filter tests');
    }

    /** @test */
    public function return_first_test_suite_groups_if_no_suite_provided(): void
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\UserTest', 'User tests');
        $this->createExampleGroup($run, 'Tests\Api\PostTest', 'Post tests');
        $this->createExampleGroup($run, 'Tests\Feature\UserTest', 'Users Feature tests');
        $this->createExampleGroup($run, 'Tests\Unit\FilterTest', 'Filter tests');

        $response = $this->get(route('enlighten.dashboard'));

        $response->assertOk();

        $response->assertViewHas('active')
            ->assertSeeText('User tests')
            ->assertSeeText('Post tests')
            ->assertDontSeeText('Users Feature tests')
            ->assertDontSeeText('Filter tests');
    }
}
