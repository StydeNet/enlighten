<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;

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
}
