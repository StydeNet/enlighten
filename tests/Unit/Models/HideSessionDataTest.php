<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\ExampleRequest;
use Tests\TestCase;

class HideSessionDataTest extends TestCase
{
    #[Test]
    function can_hide_and_overwrite_request_input_values()
    {
        $request = new ExampleRequest([
            'session_data' => [
                'token' => 'should_be_displayed',
                'secret_token' => 'should_not_be_displayed',
                'password' => 'should_be_overwriten',
            ],
        ]);

        $this->setConfig([
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

        $this->assertSame($expected, $request->session_data);
    }
}
