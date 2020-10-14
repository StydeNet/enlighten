<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Utils\FileLink;

class Example extends Model implements Statusable
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_examples';

    protected $guarded = [];

    protected $casts = [
        'count' => 'int'
    ];

    // Relationships

    public function group()
    {
        return $this->belongsTo(ExampleGroup::class);
    }

    public function http_data()
    {
//        return $this->hasMany(HttpData::class)->withDefault();
//        return $this->hasMany(HttpRequests::class)->withDefault();
        return $this->hasMany(HttpData::class);
    }

    public function exception()
    {
        return $this->hasOne(ExampleException::class)->withDefault();
    }

    public function queries()
    {
        return $this->hasMany(ExampleQuery::class);
    }

    // Accessors

    public function getFileLinkAttribute()
    {
        return FileLink::get(str_replace('\\', '/', $this->group->class_name).'.php', $this->line);
    }

    public function getStatusAttribute()
    {
        return $this->getStatus();
    }

    public function getIsHttpAttribute()
    {
        return $this->http_data->isNotEmpty();
    }

    public function getStatus(): string
    {
        if ($this->test_status == 'passed') {
            return Status::SUCCESS;
        }

        if (in_array($this->test_status, ['failure', 'error'])) {
            return Status::FAILURE;
        }

        return Status::WARNING;
    }

    public function getUrlAttribute()
    {
        return $this->group->url.'#'.$this->method_name;
    }
}
