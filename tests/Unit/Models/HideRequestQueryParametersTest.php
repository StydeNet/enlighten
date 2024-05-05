<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\ExampleRequest;
use Tests\TestCase;

class HideRequestQueryParametersTest extends TestCase
{
    #[Test]
    function can_remove_and_overwrite_query_parameters()
    {
        $request = new ExampleRequest([
            'request_query_parameters' => [
                'page' => 2,
                'host' => 'original_host',
                'token' => 'secret_token',
            ],
        ]);

        $this->setConfig([
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
        $this->assertSame($expected, $request->request_query_parameters);
    }
}
