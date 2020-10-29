<?php

namespace Tests\Integration;

class WorksWithDataProvidersTest extends TestCase
{
    /**
     * @test
     * @testWith ["dataset1"]
     *           ["dataset2"]
     */
    function can_store_information_of_tests_with_data_providers($data)
    {
        $this->assertTrue(strpos($data, 'dataset') === 0);

        // This is just a quick fix for now.
        // @TODO: Store information of datasets in the examples and improve this test.
    }
}
