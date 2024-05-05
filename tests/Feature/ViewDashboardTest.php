<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

class ViewDashboardTest extends TestCase
{
    #[Test]
    public function get_dashboard_features_view(): void
    {
        $run = $this->createRun();
        $group = $this->createExampleGroup($run, 'Tests\Api\UserTest', 'User Test');
        $this->createExample($group, 'list_users', 'passed', 'List the users');

        config(['enlighten.area_view' => 'features']);

        $response = $this->get(route('enlighten.area.show', ['run' => $run]));

        $response->assertOk()
            ->assertViewIs('enlighten::area.features')
            ->assertSeeTextInOrder([
                'All Areas',
                'User Test',
                'List the users',
            ]);
    }

    #[Test]
    public function get_dashboard_modules_view(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();
        $group = $this->createExampleGroup($run, 'Tests\Api\UserTest', 'User Test');
        $this->createExample($group, 'list_users', 'passed', 'List the users');

        config(['enlighten.area_view' => 'modules']);

        $response = $this->get(route('enlighten.area.show', ['run' => $run]));

        $response->assertOk()
            ->assertViewIs('enlighten::area.modules')
            ->assertSeeTextInOrder([
                'All Areas',
                'Users',
                'User Test',
            ])
            ->assertDontSeeText('List the users');
    }

    #[Test]
    public function get_dashboard_endpoints_view(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();
        $group = $this->createExampleGroup($run, 'Tests\Api\UserTest', 'User Test');

        $example = $this->createExample($group, 'list_users', 'passed', 'List the users');
        $this->createExampleRequest($example, [
            'request_method' => 'get',
            'request_path' => '/api/v1/users',
            'route' => '/api/v1/users'
        ]);

        $example = $this->createExample($group, 'create_users', 'passed', 'Create new users');
        $this->createExampleRequest($example, [
            'request_method' => 'post',
            'request_path' => '/api/v1/users',
            'route' => '/api/v1/users'
        ]);

        $example = $this->createExample($group, 'gets_a_user', 'passed', 'Get User by ID');
        $this->createExampleRequest($example, [
            'request_method' => 'get',
            'request_path' => '/api/v1/users/1',
            'route' => '/api/v1/users/{user}'
        ]);


        config(['enlighten.area_view' => 'endpoints']);

        $response = $this->get(route('enlighten.area.show', ['run' => $run]));

        $response->assertOk()
            ->assertViewIs('enlighten::area.endpoints')
            ->assertSeeTextInOrder([
                'All Areas',
                'get',
                '/api/v1/users',
                'post',
                '/api/v1/users',
                'get',
                '/api/v1/users/{user}'
            ]);
    }

    #[Test]
    public function redirect_to_intro_page_if_no_data_has_been_recorded_yet(): void
    {
        $response = $this->get(route('enlighten.run.index'));

        $response->assertRedirect(route('enlighten.intro'));
    }

    #[Test]
    public function get_example_groups_by_test_area(): void
    {
        $this->withoutExceptionHandling();

        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\UserTest', 'User tests');
        $this->createExampleGroup($run, 'Tests\Api\PostTest', 'Post tests');
        $this->createExampleGroup($run, 'Tests\Feature\UserTest', 'Users Feature tests');
        $this->createExampleGroup($run, 'Tests\Unit\FilterTest', 'Filter tests');

        config(['enlighten.area_view' => 'modules']);

        $response = $this->get(route('enlighten.area.show', ['run' => $run->id, 'area' => 'api']));

        $response->assertOk()
            ->assertSeeText('User tests')
            ->assertSeeText('Post tests')
            ->assertSeeText('Users Feature tests')
            ->assertSeeText('Filter tests');
    }

    #[Test]
    public function return_all_groups_if_no_area_provided(): void
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\UserTest', 'User tests');
        $this->createExampleGroup($run, 'Tests\Api\PostTest', 'Post tests');
        $this->createExampleGroup($run, 'Tests\Feature\UserTest', 'Users Feature tests');
        $this->createExampleGroup($run, 'Tests\Unit\FilterTest', 'Filter tests');

        $response = $this->get(route('enlighten.area.show', ['run' => $run->id]));

        $response->assertOk();

        $response
            ->assertSeeText('User tests')
            ->assertSeeText('Post tests')
            ->assertSeeText('Users Feature tests')
            ->assertSeeText('Filter tests');
    }

    #[Test]
    public function filter_tests_by_run_id(): void
    {
        $firstRun = $this->createRun(['head' => 'abc123']);
        $secondRun = $this->createRun(['head' => 'def456']);

        $this->createExampleGroup($firstRun, 'Tests\Api\UserTest', 'First User tests');
        $this->createExampleGroup($firstRun, 'Tests\Api\PostTest', 'First Post tests');
        $this->createExampleGroup($secondRun, 'Tests\Api\NewUserTest', 'New Users tests');
        $this->createExampleGroup($secondRun, 'Tests\Api\NewFilterTest', 'New Post tests');

        $response = $this->get(route('enlighten.area.show', ['run' => $secondRun->id]));

        $response->assertOk()
            ->assertDontSeeText('First User tests')
            ->assertDontSeeText('First Post tests')
            ->assertSeeText('New Users tests')
            ->assertSeeText('New Post tests');
    }
}
