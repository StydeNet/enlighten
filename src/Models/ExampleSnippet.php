<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\CodePrinters\HtmlPrinter;
use Styde\Enlighten\CodeSnippetExporter;

class ExampleSnippet extends Model
{
    const CLASS_NAME = '--class_name';
    const ATTRIBUTES = '--attributes';

    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_snippets';

    protected $guarded = [];

    protected $casts = [
        'result' => 'array'
    ];

    public function getResultCodeAttribute()
    {
        $output = (new CodeSnippetExporter(new HtmlPrinter))->export($this->result);

        return "<pre>{$output}</pre>";
    }
}
