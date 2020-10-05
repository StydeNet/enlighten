<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Statusable;

/**
 * @property-read Example $method_name
 * @property-read Example $title
 * @property-read Example $description
 * @property-read Example $http_data
 * @property-read Example $test_status
 */
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

    public function getStatus()
    {
        return $this->test_status;
    }

    public function getPassedAttribute()
    {
        return $this->hasPassed();
    }

    public function getFailedAttribute()
    {
        return $this->hasFailed();
    }

    public function hasPassed()
    {
        return $this->test_status === 'passed';
    }

    public function hasFailed()
    {
        return in_array($this->test_status, ['failure', 'error']);
    }
}
