<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @enlighten {"ignore": true}
 */
class IgnoreClassWithAnnotationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function does_not_export_test_classes_with_the_enlighten_ignore_annotation()
    {
        $this->assertExampleIsNotCreated();
    }
}
