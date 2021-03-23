<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use stdClass;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleSnippet;

class WorksWithDataProvidersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testWith ["dataset1"]
     *           ["dataset2"]
     */
    function can_store_information_of_tests_with_data_provider_from_annotation($data)
    {
        $this->assertTrue(strpos($data, 'dataset') === 0);

        $this->saveExampleStatus();

        $example = Example::first();

        $this->assertIsArray($example->provided_data);
        $this->assertTrue(strpos($example->provided_data[0], 'dataset') === 0);
    }

    /**
     * @test
     * @dataProvider dataProviderMethod
     */
    function can_store_information_of_tests_with_data_providers_from_method($data)
    {
        $this->assertTrue(strpos($data, 'dataset') === 0);

        $this->saveExampleStatus();

        $example = Example::first();

        $this->assertSame('Can store information of tests with data providers from method', $example->title);
        $this->assertTrue(in_array($example->data_name, ['0', '1'], true));
        $this->assertIsArray($example->provided_data);
        $this->assertTrue(strpos($example->provided_data[0], 'dataset') === 0);
    }

    public function dataProviderMethod(): array
    {
        return [
            ['dataset1'],
            ['dataset2']
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderWithObjects
     */
    function stores_information_of_objects_returned_by_data_providers($object)
    {
        $this->assertInstanceOf(StdClass::class, $object);
        $this->assertSame('dataset1', $object->property);

        $this->saveExampleStatus();

        $example = Example::first();

        $expected = [
            [
                ExampleSnippet::CLASS_NAME => 'stdClass',
                ExampleSnippet::ATTRIBUTES => [
                    'property' => 'dataset1',
                ],
            ],
        ];
        $this->assertSame($expected, $example->provided_data);
    }

    public function dataProviderWithObjects(): array
    {
        $object1 = new stdClass;
        $object1->property = 'dataset1';

        return [
            [$object1],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderWithFunctions
     */
    function stores_information_of_functions_returned_by_data_providers($function)
    {
        $this->assertInstanceOf(\Closure::class, $function);
        $this->assertSame('test', $function());

        $this->saveExampleStatus();

        $example = Example::first();

        $expected = [
            'Test Function' => [
                ExampleSnippet::FUNCTION => ExampleSnippet::ANONYMOUS_FUNCTION,
                ExampleSnippet::PARAMETERS => [],
                ExampleSnippet::RETURN_TYPE => 'string',
            ],
        ];
        $this->assertSame($expected, $example->provided_data);
    }

    public function dataProviderWithFunctions(): array
    {
        $object1 = new stdClass;
        $object1->property = 'dataset1';

        return [
            [
                'Test Function' => function (): string {
                    return 'test';
                },
            ]
        ];
    }

    /**
     * @test
     * @dataProvider providedDataWithKeys
     */
    function adds_key_from_the_data_provider_at_the_end_of_the_title($num1, $num2)
    {
        $this->assertSame(3, $num1 + $num2);

        $this->saveExampleStatus();

        $example = Example::first();

        $this->assertSame('Adds key from the data provider at the end of the title', $example->title);
        $this->assertSame('custom data key', $example->data_name);
    }

    public function providedDataWithKeys()
    {
        return [
           'custom data key' => [1, 2],
        ];
    }
}
