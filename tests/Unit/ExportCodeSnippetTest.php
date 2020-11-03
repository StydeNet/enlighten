<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Styde\Enlighten\BaseCodeSnippetPrinter;
use Styde\Enlighten\CodeSnippetExporter;
use Styde\Enlighten\Models\ExampleSnippet;
use Styde\Enlighten\Utils\ResultTransformer;
use Tests\Integration\App\Models\User;

class ExportCodeSnippetTest extends TestCase
{
    /**
     * @var CodeSnippetExporter
     */
    protected $exporter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exporter = new CodeSnippetExporter($this->newDemoCodeFormatter());
    }

    /** @test */
    function export_simple_code_snippets()
    {
        $this->assertSame('<int>2020</int>', $this->exporter->export(2020));
        $this->assertSame('<float>0.4</float>', $this->exporter->export(0.4));
        $this->assertSame('<string>"Enlighten"</string>', $this->exporter->export('Enlighten'));
        $this->assertSame('<bool>true</bool>', $this->exporter->export(true));
        $this->assertSame('<null>null</null>', $this->exporter->export(null));
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
            '    <string>"Enlighten"</string>',
            '    <float>0.4</float>',
            '<symbol>]</symbol>',
        ]);
        $this->assertSame($expected, $this->exporter->export($snippet));
    }

    /** @test */
    function export_associative_array_code_snippet()
    {
        $snippet = [
            'name' => 'Enlighten',
            'version' => 0.4,
        ];

        $expected = implode(PHP_EOL, [
            '<symbol>[</symbol>',
            '    <key>name</key> <symbol>=></symbol> <string>"Enlighten"</string>',
            '    <key>version</key> <symbol>=></symbol> <float>0.4</float>',
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
            '    <string>"Enlighten"</string>',
            '    <symbol>[</symbol>',
            '        <string>"Generate static documentation in "</string>',
            '        <float>0.4</float>',
            '    <symbol>]</symbol>',
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
            '    <property>message</property><symbol>:</symbol> <string>"this data can be collected"</string>',
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
            '    <property>message</property><symbol>:</symbol> <string>"this data can be collected"</string>',
            '    <property>nestedObject</property><symbol>:</symbol> <class>Tests\Integration\DemoNestedClassForSnippetExample</class> <symbol>{</symbol>',
            '        <property>nested</property><symbol>:</symbol> <string>"nested attribute"</string>',
            '    <symbol>}</symbol>',
            '<symbol>}</symbol>',
        ]);
        $this->assertSame($expected, $result);
    }

    /** @test */
    function export_code_with_nested_classes_and_arrays()
    {
        $snippet = ResultTransformer::toArray([
            'package' => 'Enlighten',
            'users' => collect([
                new User(['name' => 'Duilio']),
            ])
        ]);

        $expected = implode(PHP_EOL, [
            '<symbol>[</symbol>',
            '    <key>package</key> <symbol>=></symbol> <string>"Enlighten"</string>',
            '    <key>users</key> <symbol>=></symbol> <class>Illuminate\Support\Collection</class> <symbol>{</symbol>',
            '        <property>items</property><symbol>:</symbol> <symbol>[</symbol>',
            '            <class>Tests\Integration\App\Models\User</class> <symbol>{</symbol>',
            '                <property>name</property><symbol>:</symbol> <string>"Duilio"</string>',
            '            <symbol>}</symbol>',
            '        <symbol>]</symbol>',
            '    <symbol>}</symbol>',
            '<symbol>]</symbol>',
        ]);

        $result = $this->exporter->export($snippet);

        $this->assertSame($expected, $result);
    }

    private function newDemoCodeFormatter()
    {
        return new class extends BaseCodeSnippetPrinter {
            public function symbol(string $symbol): string
            {
                return "<symbol>{$symbol}</symbol>";
            }

            public function integer(int $value): string
            {
                return "<int>{$value}</int>";
            }

            public function float($value): string
            {
                return "<float>{$value}</float>";
            }

            public function string($value): string
            {
                return sprintf('<string>"%s"</string>', $value);
            }

            public function className($className): string
            {
                return "<class>{$className}</class>";
            }

            public function keyName(string $key)
            {
                return "<key>{$key}</key>";
            }

            public function propertyName(string $property)
            {
                return "<property>{$property}</property>";
            }

            public function bool($value): string
            {
                return "<bool>{$value}</bool>";
            }

            public function null(): string
            {
                return '<null>null</null>';
            }
        };
    }
}
