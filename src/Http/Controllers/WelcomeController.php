<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Mail\Markdown;
use Illuminate\Support\HtmlString;

class WelcomeController
{
    public function __invoke()
    {
        return view('enlighten::intro', [
            'content' => $this->getIntroContent()
        ]);
    }

    private function getIntroContent()
    {
        if (file_exists(base_path('ENLIGHTEN.md'))) {
            return $this->parseMarkdownFile(base_path('ENLIGHTEN.md'));
        }

        return $this->fixImagesPath($this->parseMarkdownFile(__DIR__ . '/../../../README.md'));
    }

    private function parseMarkdownFile(string $filePath): HtmlString
    {
        return Markdown::parse(file_get_contents($filePath));
    }

    private function fixImagesPath(string $content)
    {
        $baseImagePath = asset('vendor/enlighten/img') . '/';

        return str_replace('<img src="./', '<img src="'.$baseImagePath, $content);
    }
}
