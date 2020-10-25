<?php

namespace Tests\Unit\Models;

use Styde\Enlighten\Models\ExampleRequest;
use Tests\TestCase;

class HideRequestInputTest extends TestCase
{
    /** @test */
    function can_hide_and_overwrite_request_input_values()
    {
        $httpData = new ExampleRequest([
            'request_input' => [
                'username' => 'original_username',
                'email' => 'original@example.test',
                'password' => 'secret-password',
            ],
        ]);

        config([
            'enlighten.request.input' => [
                'hide' => [
                    'password',
                ],
                'overwrite' => [
                    'password' => '1234',
                    'email' => 'replaced@example.test',
                ],
            ],
        ]);

        $expected = [
            'username' => 'original_username',
            'email' => 'replaced@example.test',
        ];

        $this->assertSame($expected, $httpData->request_input);
    }
}
