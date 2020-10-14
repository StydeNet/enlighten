<?php

namespace Tests\Unit\Models;

use Styde\Enlighten\Models\HttpData;
use Tests\TestCase;

class HideSessionDataTest extends TestCase
{
    /** @test */
    function can_hide_and_overwrite_request_input_values()
    {
        $httpData = new HttpData([
            'session_data' => [
                'token' => 'should_be_displayed',
                'secret_token' => 'should_not_be_displayed',
                'password' => 'should_be_overwriten',
            ],
        ]);

        config([
            'enlighten.session' => [
                'hide' => [
                    'secret_token',
                ],
                'overwrite' => [
                    'secret_token' => 'should_not_be_displayed',
                    'password' => '******',
                ],
            ],
        ]);

        $expected = [
            'token' => 'should_be_displayed',
            'password' => '******',
        ];

        $this->assertSame($expected, $httpData->session_data);
    }
}
