<?php

namespace Tests\Unit;

use Styde\Enlighten\CodeExamples\CodeResultFormat;
use Styde\Enlighten\CodeExamples\PlainCodeResultFormat;
use Styde\Enlighten\Facades\Enlighten;
use Tests\TestCase;

class ShowCodeSnippetTest extends TestCase
{
    /** @test */
    function can_print_a_code_snippet()
    {
        $example = $this->createExample();
        $example->snippets()->create([
            'key' => 'sum',
            'code' => '2 + 3;',
            'result' => 5,
        ]);

        $this->app->singleton(CodeResultFormat::class, PlainCodeResultFormat::class);

        $expected = implode(PHP_EOL, [
            '2 + 3;',
            '<pre>5</pre>'
        ]);

        $this->assertSame($expected, trim((string) Enlighten::snippet('sum')));
    }
}
