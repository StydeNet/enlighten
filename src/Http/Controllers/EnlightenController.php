<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Example;

class EnlightenController {

    public function index()
    {
        return view('enlighten::dashboard.index');
    }

    public function show(Example $example)
    {
        return view('enlighten::example.show', ['codeExample' => $example]);
    }
}