<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Styde\Enlighten\CodeSnippetExporter;
use Styde\Enlighten\Models\ExampleSnippet;

class ExportCodeSnippetTest extends TestCase
{
    /**
     * @var CodeSnippetExporter
     */
    protected $exporter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exporter = new CodeSnippetExporter;
    }

    /** @test */
    function export_simple_code_snippets()
    {
        $this->assertSame('<int>2020</int>', $this->exporter->export(2020));
        $this->assertSame('<float>0.4</float>', $this->exporter->export(0.4));
        $this->assertSame('<string>"Enlighten"</string>', $this->exporter->export('Enlighten'));
    }

    /** @test */
    function export_simple_array_code_snippet()
    {
        $snippet = [
            'Enlighten',
            0.4,
        ];

        $expected = implode(PHP_EOL, [
            '<symbol>[</symbol>',
            '<string>"Enlighten"</string>',
            '<float>0.4</float>',
            '<symbol>]</symbol>',
        ]);
        $this->assertSame($expected, $this->exporter->export($snippet));
    }

    /** @test */
    function export_code_snippet_with_nested_arrays()
    {
        $snippet = [
            'Enlighten',
            [
                'Generate static documentation in ', 0.4
            ],
        ];

        $expected = implode(PHP_EOL, [
            '<symbol>[</symbol>',
            '<string>"Enlighten"</string>',
            '<symbol>[</symbol>',
            '<string>"Generate static documentation in "</string>',
            '<float>0.4</float>',
            '<symbol>]</symbol>',
            '<symbol>]</symbol>',
        ]);
        $this->assertSame($expected, $this->exporter->export($snippet));
    }

    /** @test */
    function export_code_snippet_with_class()
    {
        $snippet = [
            ExampleSnippet::CLASS_NAME => 'Tests\Integration\DemoClassForSnippetExample',
            ExampleSnippet::ATTRIBUTES => [
                'message' => 'this data can be collected',
            ],
        ];

        $result = $this->exporter->export($snippet);

        $expected = implode(PHP_EOL, [
            '<class>Tests\Integration\DemoClassForSnippetExample</class> <symbol>{</symbol>',
            '<property>message</property><symbol>:</symbol> <string>"this data can be collected"</string>',
            '<symbol>}</symbol>',
        ]);
        $this->assertSame($expected, $result);
    }

    /** @test */
    function export_code_snippet_with_nested_classes()
    {
        $snippet = [
            ExampleSnippet::CLASS_NAME => 'Tests\Integration\DemoClassForSnippetExample',
            ExampleSnippet::ATTRIBUTES => [
                'message' => 'this data can be collected',
                'nestedObject' => [
                    ExampleSnippet::CLASS_NAME => 'Tests\Integration\DemoNestedClassForSnippetExample',
                    ExampleSnippet::ATTRIBUTES => [
                        'nested' => 'nested attribute',
                    ]
                ]
            ],
        ];

        $result = $this->exporter->export($snippet);

        $expected = implode(PHP_EOL, [
            '<class>Tests\Integration\DemoClassForSnippetExample</class> <symbol>{</symbol>',
            '<property>message</property><symbol>:</symbol> <string>"this data can be collected"</string>',
            '<property>nestedObject</property><symbol>:</symbol> <class>Tests\Integration\DemoNestedClassForSnippetExample</class> <symbol>{</symbol>',
            '<property>nested</property><symbol>:</symbol> <string>"nested attribute"</string>',
            '<symbol>}</symbol>',
            '<symbol>}</symbol>',
        ]);
        $this->assertSame($expected, $result);
    }
}
