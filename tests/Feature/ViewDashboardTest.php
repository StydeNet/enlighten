<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Tests\TestCase;

class ViewDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_dashboard_view(): void
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\UserTest', 'User tests');

        $response = $this->get(route('enlighten.run.show', ['run' => $run]));

        $response->assertOk()
            ->assertViewIs('enlighten::suite.show');
    }

    /** @test */
    public function redirect_to_intro_page_if_no_data_has_been_recorded_yet(): void
    {
        $response = $this->get(route('enlighten.run.index'));

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

        $response = $this->get(route('enlighten.run.show', ['run' => $run->id, 'suite' => 'api']));

        $response->assertOk()
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

        $response = $this->get(route('enlighten.run.show', ['run' => $run->id]));

        $response->assertOk();

        $response
            ->assertSeeText('User tests')
            ->assertSeeText('Post tests')
            ->assertDontSeeText('Users Feature tests')
            ->assertDontSeeText('Filter tests');
    }

    /** @test */
    public function filter_tests_by_run_id(): void
    {
        $firstRun = $this->createRun(['head' => 'abc123']);
        $secondRun = $this->createRun(['head' => 'def456']);

        $this->createExampleGroup($firstRun, 'Tests\Api\UserTest', 'First User tests');
        $this->createExampleGroup($firstRun, 'Tests\Api\PostTest', 'First Post tests');
        $this->createExampleGroup($secondRun, 'Tests\Api\NewUserTest', 'New Users tests');
        $this->createExampleGroup($secondRun, 'Tests\Api\NewFilterTest', 'New Post tests');

        $response = $this->get(route('enlighten.run.show', ['run' => $secondRun->id]));

        $response->assertOk()
            ->assertDontSeeText('First User tests')
            ->assertDontSeeText('First Post tests')
            ->assertSeeText('New Users tests')
            ->assertSeeText('New Post tests');
    }

    /** @test */
    public function search_examples_by_title(): void
    {
        $this->withoutExceptionHandling();
        $firstRun = $this->createRun(['head' => 'abc123']);

        $group = $this->createExampleGroup($firstRun, 'Tests\Api\UserTest', 'User Module Tests');

        $this->createExample($group, 'create user', 'passed', 'create user');
        $this->createExample($group, 'update user', 'passed', 'update user');
        $this->createExample($group, 'delete user', 'passed', 'delete user');
        $this->createExample($group, 'search user by name', 'passed', 'search user by name');
        $this->createExample($group, 'filter user by type', 'passed', 'filter user by type');
        $this->createExample($group, 'list all users', 'passed',  'list all users');

        $response = $this->get(route('enlighten.api.search', ['run' => $firstRun->id]), [
            'search' => 'create user'
        ]);

        $response->assertOk()
            ->assertHeader('content-type', 'text/html; charset=UTF-8')
            ->assertViewIs('enlighten::search.results')
            ->assertViewHas('examples')
            ->assertSeeText('create use')
            ->assertSeeText('User Module Tests');
    }
}
