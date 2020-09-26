<?php

namespace Tests\Suites\Unit;

use Illuminate\Support\Collection;
use Styde\Enlighten\ExampleGroup;
use Styde\Enlighten\Module;
use Styde\Enlighten\ModuleCollection;
use Tests\TestCase;

class ModuleCollectionTest extends TestCase
{
    /** @test */
    function can_create_a_module_collection()
    {
        $modules = ModuleCollection::make([
            new Module('Users', ['*UserTest*', '*UsersTest*']),
            new Module('Posts', ['*PostsTest*']),
        ]);

        tap($modules->getByName('Users'), function (Module $userModule) {
            $this->assertSame('Users', $userModule->getName());
        });
    }

    /** @test */
    function add_example_groups_to_the_module_collection_items()
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

        $modules->addGroups($groupCollection);

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

    public function assertModuleHasGroups(ModuleCollection $modules, $name, array $expectedGroups)
    {
        $module = $modules->getByName($name);

        $this->assertInstanceOf(Module::class, $module);

        $this->assertSame($expectedGroups, $module->getGroup()->values()->toArray());
    }
}
