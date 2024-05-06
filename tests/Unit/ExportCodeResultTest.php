<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\CodeExamples\BaseCodeResultFormat;
use Styde\Enlighten\CodeExamples\CodeResultExporter;
use Styde\Enlighten\CodeExamples\CodeResultFormat;
use Styde\Enlighten\CodeExamples\CodeResultTransformer;
use Styde\Enlighten\Models\ExampleSnippet;
use Tests\Integration\App\Models\User;
use Tests\TestCase;

class ExportCodeResultTest extends TestCase
{
    protected CodeResultExporter $exporter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(CodeResultFormat::class, $this->newDemoCodeResultFormat());

        $this->exporter = $this->app->make(CodeResultExporter::class);
    }

    #[Test]
    function export_simple_code_snippets(): void
    {
        $this->assertExports('<int>2020</int>', 2020);
        $this->assertExports('<float>0.4</float>', 0.4);
        $this->assertExports('<string>"Enlighten"</string>', 'Enlighten');
        $this->assertExports('<bool>true</bool>', true);
        $this->assertExports('<null>null</null>', null);
    }

    #[Test]
    function export_simple_array_code_snippet(): void
    {
        $expected = [
            '<symbol>[</symbol>',
            '    <string>"Enlighten"</string><symbol>,</symbol>',
            '    <float>0.4</float><symbol>,</symbol>',
            '<symbol>]</symbol>',
        ];

        $data = [
            'Enlighten',
            0.4,
        ];

        $this->assertExports($expected, $data);
    }

    #[Test]
    function export_associative_array_code_snippet(): void
    {
        $data = [
            'name' => 'Enlighten',
            'version' => 0.4,
        ];

        $expected = [
            '<symbol>[</symbol>',
            '    <string>"name"</string> <symbol>=></symbol> <string>"Enlighten"</string><symbol>,</symbol>',
            '    <string>"version"</string> <symbol>=></symbol> <float>0.4</float><symbol>,</symbol>',
            '<symbol>]</symbol>',
        ];

        $this->assertExports($expected, $data);
    }

    #[Test]
    function export_code_snippet_with_nested_arrays(): void
    {
        $data = [
            'Enlighten',
            [
                'Generate static documentation in ', 0.4
            ],
        ];

        $expected = [
            '<symbol>[</symbol>',
            '    <string>"Enlighten"</string><symbol>,</symbol>',
            '    <symbol>[</symbol>',
            '        <string>"Generate static documentation in "</string><symbol>,</symbol>',
            '        <float>0.4</float><symbol>,</symbol>',
            '    <symbol>]</symbol><symbol>,</symbol>',
            '<symbol>]</symbol>',
        ];

        $this->assertExports($expected, $data);
    }

    #[Test]
    function export_code_snippet_with_class(): void
    {
        $data = [
            ExampleSnippet::CLASS_NAME => 'Tests\Integration\DemoClassForSnippetExample',
            ExampleSnippet::ATTRIBUTES => [
                'message' => 'this data can be collected',
            ],
        ];

        $expected = [
            '<class>Tests\Integration\DemoClassForSnippetExample</class> <symbol>{</symbol>',
            '    <property>message</property><symbol>:</symbol> <string>"this data can be collected"</string><symbol>,</symbol>',
            '<symbol>}</symbol>',
        ];

        $this->assertExports($expected, $data);
    }

    #[Test]
    function export_code_snippet_with_nested_classes(): void
    {
        $data = [
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

        $expected = [
            '<class>Tests\Integration\DemoClassForSnippetExample</class> <symbol>{</symbol>',
            '    <property>message</property><symbol>:</symbol> <string>"this data can be collected"</string><symbol>,</symbol>',
            '    <property>nestedObject</property><symbol>:</symbol> <class>Tests\Integration\DemoNestedClassForSnippetExample</class> <symbol>{</symbol>',
            '        <property>nested</property><symbol>:</symbol> <string>"nested attribute"</string><symbol>,</symbol>',
            '    <symbol>}</symbol><symbol>,</symbol>',
            '<symbol>}</symbol>',
        ];

        $this->assertExports($expected, $data);
    }

    #[Test]
    function export_code_with_nested_classes_and_arrays(): void
    {
        $data = CodeResultTransformer::export([
            'package' => 'Enlighten',
            'users' => collect([
                new User(['name' => 'Duilio']),
            ])
        ]);

        $expected = [
            '<symbol>[</symbol>',
            '    <string>"package"</string> <symbol>=></symbol> <string>"Enlighten"</string><symbol>,</symbol>',
            '    <string>"users"</string> <symbol>=></symbol> <class>Illuminate\Support\Collection</class> <symbol>{</symbol>',
            '        <property>items</property><symbol>:</symbol> <symbol>[</symbol>',
            '            <class>Tests\Integration\App\Models\User</class> <symbol>{</symbol>',
            '                <property>name</property><symbol>:</symbol> <string>"Duilio"</string><symbol>,</symbol>',
            '            <symbol>}</symbol><symbol>,</symbol>',
            '        <symbol>]</symbol><symbol>,</symbol>',
            '    <symbol>}</symbol><symbol>,</symbol>',
            '<symbol>]</symbol>',
        ];

        $this->assertExports($expected, $data);
    }

    private function assertExports($segments, $value): void
    {
        $expectedCode = collect($segments)
            ->map(function ($segment) {
                return "    {$segment}";
            })
            ->prepend('<pre>')
            ->add('</pre>')
            ->join(PHP_EOL);

        $this->assertSame($expectedCode, $this->exporter->export($value));
    }

    private function newDemoCodeResultFormat()
    {
        return new class extends BaseCodeResultFormat {
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
