<?php

namespace Styde\Enlighten\View;

use Illuminate\Support\HtmlString;
use Styde\Enlighten\Models\ExampleSnippet;

class CodeSnippet
{
    /**
     * @var ExampleSnippet|null
     */
    protected $snippet;

    /**
     * @var string
     */
    private $template;

    public function __construct($key, $version = null, $template = 'default')
    {
        $this->snippet = ExampleSnippet::where('key', $key)
            ->first();

        $this->template = $template;
    }

    public function render()
    {
        if (is_null($this->snippet)) {
            return '';
        }

        return view("enlighten::snippets.{$this->template}", [
            'snippet' => $this->snippet,
        ]);
    }
}
