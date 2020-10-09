<?php

namespace Tests\Unit\Models;

use Styde\Enlighten\Models\HttpData;
use Tests\TestCase;

class HideResponseHeadersTest extends TestCase
{
    /** @test */
    function can_hide_and_overwrite_response_headers()
    {
        $httpData = new HttpData([
            'response_headers' => [
                'secret-token' => 'this-should-be-removed',
                'token' => 'this-value-should-be-replaced',
                'content-type' => 'application/json',
            ],
        ]);

        config([
            'enlighten.response.headers' => [
                'hide' => ['secret-token'],
                'overwrite' => [
                    'secret-token' => 'this-should-not-be-present',
                    'token' => '******',
                    'key-not-present' => 'this-value-should-not-be-included',
                ],
            ]
        ]);

        $this->assertSame([
            'token' => '******',
            'content-type' => 'application/json',
        ], $httpData->response_headers);
    }
}
