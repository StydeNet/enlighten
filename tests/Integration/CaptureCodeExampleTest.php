<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleSnippet;

class CaptureCodeExampleTest extends TestCase
{
    /** @test */
    function captures_code_example()
    {
        $sum = enlighten(function ($a, $b) {
            return $a + $b;
        }, 1, 2);

        $this->assertSame(3, $sum);

        $this->saveTestExample();

        $example = Example::first();

        $this->assertNotNull($example, 'An expected example was not created.');
        $this->assertSame('captures_code_example', $example->method_name);

        tap($example->snippets->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame(['a' => 1, 'b' => 2], $snippet->params);
            $this->assertSame('$a + $b', $snippet->code);
            $this->assertSame(3, $snippet->result);
        });
    }

    /** @test */
    function captures_code_with_a_default_value()
    {
        $sum = enlighten(function ($a, $b = 3) {
            return $a + $b;
        }, 2);

        $this->assertSame(5, $sum);

        $this->saveTestExample();

        tap(ExampleSnippet::first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame(['a' => 2, 'b' => 3], $snippet->params);
            $this->assertSame('$a + $b', $snippet->code);
            $this->assertSame(5, $snippet->result);
        });
    }
}
