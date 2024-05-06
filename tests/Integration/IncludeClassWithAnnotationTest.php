<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;

/**
 * @enlighten
 */
class IncludeClassWithAnnotationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setConfig([
            'enlighten.tests.ignore' => ['*'],
        ]);
    }

    #[Test]
    function export_test_classes_with_the_enlighten_annotation_even_if_its_ignored_in_the_config(): void
    {
        $this->assertExampleIsCreated();
    }
}
