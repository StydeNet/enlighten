<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read Example $method_name
 * @property-read Example $title
 * @property-read Example $description
 * @property-read Example $http_data
 * @property-read Example $test_status
 */
class Example extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_examples';

    protected $guarded = [];

    // Relationships

    public function group()
    {
        return $this->belongsTo(ExampleGroup::class);
    }

    public function http_data()
    {
        return $this->hasOne(HttpData::class)->withDefault();
    }

    // Accessors

    public function getFileLinkAttribute()
    {
        $path = str_replace('\\', '/', $this->group->class_name).'.php';

        return 'phpstorm://open?file='.urlencode(base_path($path)).'&ampline='.$this->line;
    }

    public function getIsHttpAttribute()
    {
        return $this->http_data->exists;
    }

    public function getPassedAttribute()
    {
        return $this->test_status === 'passed';
    }

    public function getFailedAttribute()
    {
        return in_array($this->test_status, ['failure', 'error']);
    }
}
