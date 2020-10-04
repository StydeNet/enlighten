<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Mail\Markdown;

class WelcomeController extends Controller
{
    public function intro()
    {
        if (file_exists(base_path('ENLIGHTEN.md'))) {
            $content = Markdown::parse(base_path('ENLIGHTEN.md'));
        } else {
            $content = Markdown::parse(file_get_contents(__DIR__ . '/../../../README.md'));
        }

        return view('enlighten::intro', [
            'content' => $content,
            'tabs' => $this->getTabs()
        ]);
    }
}
