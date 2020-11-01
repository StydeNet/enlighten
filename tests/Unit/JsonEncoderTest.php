<?php

namespace Tests\Unit;

use Styde\Enlighten\Utils\JsonFormatter;
use Tests\TestCase;

class JsonEncoderTest extends TestCase
{
    /** @test */
    public function preview_unascaped_unicode_values(): void
    {
        $entry = [
            'foo' => '英语',
            'bar' => '英语'
        ];

        $output = JsonFormatter::prettify($entry);

        $this->assertSame(implode("\n", [
            '{',
            '    "foo": "英语",',
            '    "bar": "英语"',
            '}',
        ]), $output);
    }
}
