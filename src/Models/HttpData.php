<?php

namespace Styde\Enlighten\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HttpData extends Model
{
    use ReplacesValues;

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

    public function getRequestHeadersAttribute($value)
    {
        return $this->replaceValues($value, config('enlighten.request.headers'));
    }

    public function getRequestInputAttribute($value)
    {
        return $this->replaceValues($value, config('enlighten.request.input'));
    }

    public function getRequestQueryParametersAttribute($value)
    {
        return $this->replaceValues($value, config('enlighten.request.query'));
    }

    public function getResponseHeadersAttribute($value)
    {
        return $this->replaceValues($value, config('enlighten.response.headers'));
    }

    public function getSessionDataAttribute($value)
    {
        return $this->replaceValues($value, config('enlighten.session'));
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
        // If the response has a redirection status, we should comment
        // the meta http-equiv HTML tag to avoid triggering any HTML
        // redirection, when displaying the previews to the users.
        if ($this->has_redirection_status) {
            return preg_replace('@<meta http-equiv="refresh" .*?>@', '<!--$0-->', $this->response_body);
        }

        return $this->response_body;
    }

    public function getValidationErrorsAttribute()
    {
        return $this->session_data['errors'] ?? [];
    }

    public function getHashAttribute()
    {
        return "test_{$this->id}";
    }
}
