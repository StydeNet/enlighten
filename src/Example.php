<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property-read Example $title
 * @property-read Example $description
 * @property-read Example $request_headers
 * @property-read Example $request_method
 * @property-read Example $request_path
 * @property-read Example $request_query_parameters
 * @property-read Example $request_input
 * @property-read Example $route
 * @property-read Example $route_parameters
 * @property-read Example $response_headers
 * @property-read Example $response_status
 * @property-read Example $response_body
 * @property-read Example $response_template
 * @property-read Example $response_type
 * @property-read Example $full_path
 * @property-read Example $has_redirection_status
 * @property-read Example $redirection_location
 */
class Example extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_examples';

    protected $guarded = [];

    protected $casts = [
        'request_headers' => 'array',
        'request_query_parameters' => 'array',
        'request_input' => 'array',
        'route_parameters' => 'array',
        'response_headers' => 'array',
        'session_data' => 'array',
    ];

    // Relationships

    public function group()
    {
        return $this->belongsTo(ExampleGroup::class);
    }

    // Accessors

    public function getFullPathAttribute()
    {
        if (empty($this->request_query_parameters)) {
            return $this->request_path;
        }

        return $this->request_path.'?'.http_build_query($this->request_query_parameters);
    }

    public function getHasRedirectionStatusAttribute()
    {
        return $this->response_status >= 300 && $this->response_status < 400;
    }

    public function getRedirectionLocationAttribute()
    {
        return $this->response_headers['location'] ?? null;
    }

    public function getResponseTypeAttribute()
    {
        $contentTypes = [
            'text/html' => 'HTML',
            '/json' => 'JSON',
            'text/plain' => 'TEXT'
        ];

        return collect($contentTypes)->first(function ($label, $type) {
            return Str::contains($this->response_headers['content-type'][0], $type);
        });
    }

    public function getResponseBodyAttribute()
    {
        if ($this->response_type === 'JSON') {
            return json_decode($this->attributes['response_body'], JSON_OBJECT_AS_ARRAY);
        }

        return $this->attributes['response_body'];
    }

    public function getValidationErrorsAttribute()
    {
        return $this->session_data['errors'] ?? [];
    }

    public function getPassedAttribute()
    {
        return $this->test_status === 'passed';
    }
}
