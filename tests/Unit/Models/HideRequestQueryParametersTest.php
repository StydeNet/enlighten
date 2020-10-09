<?php

namespace Tests\Unit\Models;

use Styde\Enlighten\Models\HttpData;
use Tests\TestCase;

class HideRequestQueryParametersTest extends TestCase
{
    /** @test */
    function can_remove_and_overwrite_query_parameters()
    {
        $httpData = new HttpData([
            'request_query_parameters' => [
                'page' => 2,
                'host' => 'original_host',
                'token' => 'secret_token',
            ],
        ]);

        config([
            'enlighten.request.query' => [
                'hide' => [
                    'token',
                ],
                'overwrite' => [
                    'token' => '1234',
                    'host' => 'replaced_host',
                ],
            ],
        ]);

        $expected = ['page' => 2, 'host' => 'replaced_host'];
        $this->assertSame($expected, $httpData->request_query_parameters);
    }
}
