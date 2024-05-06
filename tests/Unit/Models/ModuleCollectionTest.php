<?php

namespace Tests\Unit\Models;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\Endpoint;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Module;
use Styde\Enlighten\Models\ModuleCollection;
use Tests\TestCase;

class ModuleCollectionTest extends TestCase
{
    #[Test]
    function can_create_a_module_collection(): void
    {
        $modules = ModuleCollection::make([
            new Module('Users', ['*UserTest*', '*UsersTest*']),
            new Module('Posts', ['*PostsTest*']),
        ]);

        tap($modules->getByName('Users'), function (Module $userModule) {
            $this->assertSame('Users', $userModule->name);
        });
    }

    #[Test]
    function add_example_groups_to_the_modules_in_the_module_collection(): void
    {
        $modules = ModuleCollection::make([
            new Module('Users', ['*UserTest*', '*UsersTest*']),
            new Module('Posts', ['*PostsTest*']),
            new Module('Search', ['Search*']),
        ]);

        $groupCollection = Collection::make([
            new ExampleGroup(['class_name' => 'ListUsersTest']),
            new ExampleGroup(['class_name' => 'UpdatePostsTest']),
            new ExampleGroup(['class_name' => 'ListProjectsTest']),
            new ExampleGroup(['class_name' => 'SearchUsersTest']),
            new ExampleGroup(['class_name' => 'CreateUserTest']),
            new ExampleGroup(['class_name' => 'SearchTest']),
        ]);

        $modules->wrapGroups($groupCollection);

        $this->assertModuleHasGroups($modules, 'Users', [
            ['class_name' => 'ListUsersTest'],
            ['class_name' => 'SearchUsersTest'],
            ['class_name' => 'CreateUserTest'],
        ]);

        $this->assertModuleHasGroups($modules, 'Posts', [
            ['class_name' => 'UpdatePostsTest'],
        ]);

        $this->assertModuleHasGroups($modules, 'Search', [
            ['class_name' => 'SearchTest']
        ]);

        $this->assertModuleHasGroups($modules, 'Other Modules', [
            ['class_name' => 'ListProjectsTest']
        ]);
    }

    public function assertModuleHasGroups(ModuleCollection $modules, $name, array $expectedGroups): void
    {
        $module = $modules->getByName($name);

        $this->assertInstanceOf(Module::class, $module);

        $this->assertSame($expectedGroups, $module->groups->values()->toArray());
    }

    #[Test]
    public function remove_modules_without_example_groups_from_the_module_collection(): void
    {
        $modules = ModuleCollection::make([
            new Module('Users', ['*UserTest*', '*UsersTest*']),
            new Module('Posts', ['*EMPTY*']),
        ]);

        $groupCollection = Collection::make([
            new ExampleGroup(['class_name' => 'ListUsersTest']),
        ]);

        $modules = $modules->wrapGroups($groupCollection)->whereHasGroups();

        $this->assertSame(1, $modules->count());
    }

    #[Test]
    function add_endpoint_groups_to_the_modules_in_the_module_collection(): void
    {
        $modules = ModuleCollection::make([
            new Module('Users', [], ['/users*', '/user/*']),
            new Module('Videos', [], ['/videos*']),
        ]);

        $endpoints = Collection::make([
            new Endpoint('GET', '/users'),
            new Endpoint('POST', '/users'),
            new Endpoint('GET', '/user/{id}'),
            new Endpoint('POST', '/videos/{category}'),
            new Endpoint('POST', '/likes'),
        ]);

        $modules->wrapGroups($endpoints);

        $this->assertModuleHasEndpoints($modules, 'Users', ['GET /users', 'POST /users', 'GET /user/{id}']);
        $this->assertModuleHasEndpoints($modules, 'Videos', ['POST /videos/{category}']);
        $this->assertModuleHasEndpoints($modules, 'Other Modules', ['POST /likes']);
    }

    public function assertModuleHasEndpoints(ModuleCollection $modules, $name, array $expectedGroups): void
    {
        $module = $modules->getByName($name);

        $this->assertInstanceOf(Module::class, $module);

        $this->assertSame($expectedGroups, $module->groups->map->getSignature()->values()->all());
    }

    #[Test]
    public function remove_modules_without_endpoint_groups_from_the_module_collection(): void
    {
        $modules = ModuleCollection::make([
            new Module('Users', ['*UserTest*', '*UsersTest*']),
            new Module('Posts', ['*EMPTY*']),
        ]);

        $groupCollection = Collection::make([
            new ExampleGroup(['class_name' => 'ListUsersTest']),
        ]);

        $modules = $modules->wrapGroups($groupCollection)->whereHasGroups();

        $this->assertSame(1, $modules->count());
    }
}
