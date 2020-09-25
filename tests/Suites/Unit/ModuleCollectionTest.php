<?php

namespace Tests\Suites\Unit;

use Styde\Enlighten\Example;
use Styde\Enlighten\ExampleGroupCollection;
use Styde\Enlighten\Module;
use Styde\Enlighten\ModuleCollection;
use Tests\TestCase;

class ModuleCollectionTest extends TestCase
{
    /** @test */
    function can_create_a_module_collection()
    {
        $modules = ModuleCollection::make([
            [
                'name' => 'Users',
                'pattern' => ['*UserTest*', '*UsersTest*'],
            ],
            [
                'name' => 'Posts',
                'pattern' => ['*PostsTest*'],
            ],
        ]);

        tap($modules->getByName('Users'), function (Module $userModule) {
            $this->assertSame('Users', $userModule->getName());
        });
    }

    /** @test */
    function add_example_groups_to_the_module_collection_items()
    {
        $modules = ModuleCollection::make([
            [
                'name' => 'Users',
                'pattern' => ['*UserTest*', '*UsersTest*'],
            ],
            [
                'name' => 'Posts',
                'pattern' => ['*PostsTest*'],
            ],
            [
                'name' => 'Search',
                'pattern' => ['Search*'],
            ],
        ]);

        $groupCollection = ExampleGroupCollection::make([
            new Example(['class_name' => 'ListUsersTest']),
            new Example(['class_name' => 'UpdatePostsTest']),
            new Example(['class_name' => 'ListProjectsTest']),
            new Example(['class_name' => 'SearchUsersTest']),
            new Example(['class_name' => 'CreateUserTest']),
            new Example(['class_name' => 'SearchTest']),
        ]);

        $modules->addGroups($groupCollection);

        $this->assertModuleHasGroups($modules->getByName('Users'), [
            ['class_name' => 'ListUsersTest'],
            ['class_name' => 'SearchUsersTest'],
            ['class_name' => 'CreateUserTest'],
        ]);

        $this->assertModuleHasGroups($modules->getByName('Posts'), [
            ['class_name' => 'UpdatePostsTest'],
        ]);

        $this->assertModuleHasGroups($modules->getByName('Search'), [
            ['class_name' => 'SearchTest']
        ]);

        $this->assertModuleHasGroups($modules->getByName('Other Modules'), [
            ['class_name' => 'ListProjectsTest']
        ]);
    }

    public function assertModuleHasGroups(Module $module, array $expectedGroups)
    {
        $this->assertSame($expectedGroups, $module->getGroup()->values()->toArray());
    }
}
