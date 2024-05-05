<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\ExampleRequest;
use Tests\TestCase;

class HideRequestInputTest extends TestCase
{
    #[Test]
    function can_hide_and_overwrite_request_input_values()
    {
        $request = new ExampleRequest([
            'request_input' => [
                'username' => 'original_username',
                'email' => 'original@example.test',
                'password' => 'secret-password',
            ],
        ]);

        $this->setConfig([
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

        $this->assertSame($expected, $request->request_input);
    }
}
