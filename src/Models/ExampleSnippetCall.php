<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\VarDumper\VarDumper;

class ExampleSnippetCall extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_snippet_calls';

    protected $guarded = [];

    protected $casts = [
        'arguments' => 'array',
        'result' => 'array',
    ];

    public function getArgumentsCodeAttribute(): string
    {
        return collect($this->arguments)->map(function ($value, $key) {
            return '$'.$key.' = '.var_export($value, true).';';
        })->implode("\n");
    }

    public function getResultCodeAttribute(): string
    {
        return OutputFormatter::format($this->result);
    }
}
