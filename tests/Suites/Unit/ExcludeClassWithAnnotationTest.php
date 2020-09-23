<?php

namespace Tests\Suites\Unit;

use Tests\TestCase;

/**
 * @enlighten {"exclude": true}
 */
class ExcludeClassWithAnnotationTest extends TestCase
{
    /** @test */
    function does_not_export_test_classes_with_the_enlighten_exclude_annotation()
    {
        $this->assertExampleIsNotCreated();
    }
}
