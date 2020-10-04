<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Mail\Markdown;
use Illuminate\Support\Str;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Module;
use Styde\Enlighten\TestSuite;

class EnlightenController {

    public function index(string $suite = null)
    {
        $tabs = $this->getTabs();

        if ($tabs->isEmpty()) {
            return redirect(route('enlighten.intro'));
        }

        if ($suite === null) {
            $suite = $tabs->first();
        } else {
            $suite = $tabs->firstWhere('slug', $suite);
        }

        if ($suite === null) {
            return redirect(route('enlighten.dashboard'));
        }

        $groups = ExampleGroup::findByTestSuite($suite);

        $modules = Module::all();

        $modules->addGroups($groups);

        return view('enlighten::dashboard.index', [
            'modules' => $modules->whereHasGroups(),
            'tabs' => $tabs,
            'active' => $suite->slug,
            'title' => 'Dashboard'
        ]);
    }

    public function show(string $suite, ExampleGroup $group)
    {
        return view('enlighten::group.show', [
            'group' => $group,
            'title' => $group->title,
            'tabs' => $this->getTabs(),
            'active' => $suite
        ]);
    }

    public function intro()
    {
        if (file_exists(base_path('ENLIGHTEN.md'))) {
            $content = Markdown::parse(base_path('ENLIGHTEN.md'));
        } else {
            $content = Markdown::parse(file_get_contents(__DIR__ . '/../README.md'));
        }

        return view('enlighten::intro', [
            'content' => $content,
            'tabs' => $this->getTabs()
        ]);
    }

    protected function getTabs()
    {
        return TestSuite::all()->mapWithKeys(function ($value, $key) {
            return [Str::slug($key) => $value];
        });
    }

}
