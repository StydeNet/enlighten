<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property-read HttpData $request_headers
 * @property-read HttpData $request_method
 * @property-read HttpData $request_path
 * @property-read HttpData $request_query_parameters
 * @property-read HttpData $request_input
 * @property-read HttpData $route
 * @property-read HttpData $route_parameters
 * @property-read HttpData $response_headers
 * @property-read HttpData $response_status
 * @property-read HttpData $response_body
 * @property-read HttpData $response_preview
 * @property-read HttpData $response_template
 * @property-read HttpData $response_type
 * @property-read HttpData $full_path
 * @property-read HttpData $has_redirection_status
 * @property-read HttpData $redirection_location
 * @property-read HttpData $session_data
 */
class HttpData extends Model
{
    protected $connection = 'enlighten';

    protected $table = 'enlighten_http_data';

    protected $guarded = [];

    protected $casts = [
        'request_headers' => 'array',
        'request_query_parameters' => 'array',
        'request_input' => 'array',
        'route_parameters' => 'array',
        'response_headers' => 'array',
        'session_data' => 'array',
    ];

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
        return $this->response_headers['location'][0] ?? null;
    }

    public function getResponseTypeAttribute()
    {
        if (empty($this->response_headers['content-type'])) {
            return 'UNDEFINED';
        }

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

    public function getResponsePreviewAttribute()
    {
        if ($this->has_redirection_status) {
            return preg_replace('@<meta http-equiv="refresh" .*?>@', '<!--$0-->', $this->response_body);
        }

        return $this->response_body;
    }

    public function getValidationErrorsAttribute()
    {
        return $this->session_data['errors'] ?? [];
    }
}
