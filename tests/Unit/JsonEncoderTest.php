<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Utils\JsonFormatter;
use Tests\TestCase;

class JsonEncoderTest extends TestCase
{
    #[Test]
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
