<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Utils\FileLink;

class ExampleException extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_exceptions';

    protected $guarded = [];

    protected $casts = [
        'code' => 'int',
        'trace' => 'array',
        'extra' => 'array',
    ];

    public function getFileLinkAttribute()
    {
        if (empty($this->file)) {
            return '';
        }

        return FileLink::get($this->file);
    }

    public function getValidationErrorsAttribute()
    {
        return $this->extra['errors'] ?? [];
    }
}
