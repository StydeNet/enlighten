<?php

namespace Tests\Unit;

use Illuminate\Support\Collection;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
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

        $this->assertSame($expectedGroups, $module->getGroups()->values()->toArray());
    }

    /** @test */
    public function remove_empty_modules_from_collection(): void
    {
        $modules = ModuleCollection::make([
            new Module('Users', ['*UserTest*', '*UsersTest*']),
            new Module('Posts', ['*EMPTY*']),
        ]);

        $groupCollection = Collection::make([
            new ExampleGroup(['class_name' => 'ListUsersTest']),
        ]);

        $modules = $modules->addGroups($groupCollection)->whereHasGroups();

        $this->assertSame(1, $modules->count());
    }

    /** @test */
    public function get_stats_for_group(): void
    {
        $run = $this->createRun();
        $group = $this->createExampleGroup($run);

        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'first_test', 'test_status' => 'passed']);
        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'second_test', 'test_status' => 'passed']);
        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'third_test', 'test_status' => 'passed']);
        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'fourth_test', 'test_status' => 'passed']);

        $module = new Module('All', ['*']);
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(4, $module->getTestsCount());
        $this->assertSame('passed', $module->getStatus());

        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'sixth_test', 'test_status' => 'skipped']);
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(5, $module->getTestsCount());
        $this->assertSame('warned', $module->getStatus());


        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'fifth_test', 'test_status' => 'error']);
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(6, $module->getTestsCount());
        $this->assertSame('failed', $module->getStatus());
    }
}
