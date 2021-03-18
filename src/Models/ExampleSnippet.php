<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\CodeExamples\CodeResultExporter;

class ExampleSnippet extends Model
{
    const CLASS_NAME = '--class_name';
    const ATTRIBUTES = '--attributes';

    const FUNCTION = '--function';
    const RETURN_TYPE = '--return-type';
    const ANONYMOUS_FUNCTION = 'Anonymous Function';
    const PARAMETERS = '--parameters';
    const PARAMETER = '--parameter';
    const TYPE = '--type';
    const OPTIONAL = '--optional';
    const DEFAULT = '--default';

    protected $connection = 'enlighten';

    protected $table = 'enlighten_example_snippets';

    protected $guarded = [];

    protected $casts = [
        'result' => 'array'
    ];

    public function getResultCodeAttribute()
    {
        return app(CodeResultExporter::class)->export($this->result);
    }
}
