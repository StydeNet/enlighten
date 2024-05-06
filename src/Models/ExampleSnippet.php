<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\CodeExamples\CodeResultExporter;

class ExampleSnippet extends Model
{
    public const CLASS_NAME = '--class_name';
    public const ATTRIBUTES = '--attributes';

    public const FUNCTION = '--function';
    public const RETURN_TYPE = '--return-type';
    public const ANONYMOUS_FUNCTION = 'Anonymous Function';
    public const PARAMETERS = '--parameters';
    public const PARAMETER = '--parameter';
    public const TYPE = '--type';
    public const OPTIONAL = '--optional';
    public const DEFAULT = '--default';

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
