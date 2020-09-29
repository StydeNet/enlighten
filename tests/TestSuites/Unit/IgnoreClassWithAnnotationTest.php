<?php

namespace Tests\TestSuites\Unit;

use Tests\TestCase;

/**
 * @enlighten {"ignore": true}
 */
class IgnoreClassWithAnnotationTest extends TestCase
{
    /** @test */
    function does_not_export_test_classes_with_the_enlighten_ignore_annotation()
    {
        $this->assertExampleIsNotCreated();
    }
}
