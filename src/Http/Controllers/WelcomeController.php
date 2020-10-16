<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Mail\Markdown;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        return view('enlighten::intro', [
            'content' => $this->getIntroContent(),
            'tabs' => $this->getTabs()
        ]);
    }

    private function getIntroContent()
    {
        if (file_exists(base_path('ENLIGHTEN.md'))) {
            return $this->parseMarkdownFile(base_path('ENLIGHTEN.md'));
        } else {
            return $this->fixImagesPath($this->parseMarkdownFile(__DIR__ . '/../../../README.md'));
        }
    }

    private function parseMarkdownFile(string $filePath)
    {
        return Markdown::parse(file_get_contents($filePath));
    }

    private function fixImagesPath(string $content)
    {
        $baseImagePath = asset('vendor/enlighten/img') . '/';

        return str_replace('<img src="./', '<img src="'.$baseImagePath, $content);
    }
}
