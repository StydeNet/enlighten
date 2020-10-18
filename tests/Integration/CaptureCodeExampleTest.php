<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleSnippet;

class CaptureCodeExampleTest extends TestCase
{
    /** @test */
    function captures_code_example()
    {
        $sum = enlighten(function ($a, ?int $b = 0) {
            return $a + $b;
        }, 1, 2);

        $this->assertSame(3, $sum);

        $this->saveTestExample();

        $example = Example::first();

        $this->assertNotNull($example, 'An expected example was not created.');
        $this->assertSame('captures_code_example', $example->method_name);

        tap($example->snippets->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame(3, $snippet->result);
            $this->assertSame('$a + $b', $snippet->code);
            $this->assertSame([
                [
                    'name' => 'a',
                    'type' => null,
                    'nullable' => true,
                    'has_default' => false,
                    'default' => null,
                ],
                [
                    'name' => 'b',
                    'type' => 'int',
                    'nullable' => true,
                    'has_default' => true,
                    'default' => 0,
                ]
            ], $snippet->params);
            $this->assertSame([1, 2], $snippet->args);
        });
    }
}
