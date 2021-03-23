<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Contracts\Example as ExampleContract;
use Styde\Enlighten\Utils\FileLink;

class Example extends Model implements ExampleContract, Statusable
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_examples';

    protected $guarded = [];

    protected $casts = [
        'provided_data' => 'array',
        'count' => 'int',
        'order_num' => 'int',
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

    public function getTitleAttribute($title)
    {
        if (is_null($this->data_name)) {
            return $title;
        }

        if (is_numeric($this->data_name)) {
            return sprintf('%s (dataset #%s)', $title, $this->data_name);
        }

        return sprintf('%s (%s)', $title, $this->data_name);
    }

    public function getSignatureAttribute()
    {
        return $this->group->class_name.'::'.$this->method_name;
    }

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

    public function getOrderAttribute()
    {
        return [$this->order_num, $this->id];
    }

    // Contract

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getTitle(): string
    {
        return "{$this->group->title} - {$this->title}";
    }

    public function getStatus(): string
    {
        return $this->attributes['status'] ?? Status::UNKNOWN;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
