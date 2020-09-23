<?php

namespace Styde\Enlighten;

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
