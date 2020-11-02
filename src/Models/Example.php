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

    public function requests()
    {
        return $this->hasMany(ExampleRequest::class);
    }

    public function exception()
    {
        return $this->hasOne(ExampleException::class)->withDefault();
    }

    public function queries()
    {
        return $this->hasMany(ExampleQuery::class);
    }

    public function snippets()
    {
        return $this->hasMany(ExampleSnippet::class);
    }

    // Accessors

    public function getHasExceptionAttribute()
    {
        return $this->exception->exists;
    }

    public function getFileLinkAttribute()
    {
        return FileLink::get(str_replace('\\', '/', $this->group->class_name).'.php', $this->line);
    }

    public function getIsHttpAttribute()
    {
        return $this->requests->isNotEmpty();
    }

    public function getStatus(): string
    {
        return $this->attributes['status'] ?? Status::UNKNOWN;
    }

    public function getUrlAttribute()
    {
        return route('enlighten.method.show', [
            $this->group->run_id,
            $this->group->slug,
            $this->slug,
        ]);
    }

    public function getOrphanQueriesAttribute()
    {
        return $this->queries->where('request_id', null);
    }
}
