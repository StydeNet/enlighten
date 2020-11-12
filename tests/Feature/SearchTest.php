<?php

namespace Tests\Feature;

class SearchTest extends TestCase
{
    /** @test */
    public function search_examples_by_title(): void
    {
        $firstRun = $this->createRun(['head' => 'abc123']);

        $group = $this->createExampleGroup($firstRun, 'Tests\Api\UserTest', 'User Module Tests');

        $this->createExample($group, 'create user', 'passed', 'create user');
        $this->createExample($group, 'update user', 'passed', 'update user');
        $this->createExample($group, 'delete user', 'passed', 'delete user');
        $this->createExample($group, 'search user by name', 'passed', 'search user by name');
        $this->createExample($group, 'filter user by type', 'passed', 'filter user by type');
        $this->createExample($group, 'list all users', 'passed', 'list all users');

        $response = $this->get(route('enlighten.api.search', $firstRun).'?search=create%20user');

        $response->assertOk()
            ->assertHeader('content-type', 'text/html; charset=UTF-8')
            ->assertViewIs('enlighten::search.results')
            ->assertViewHas('examples')
            ->assertSeeText('create use')
            ->assertSeeText('User Module Tests');
    }
}
