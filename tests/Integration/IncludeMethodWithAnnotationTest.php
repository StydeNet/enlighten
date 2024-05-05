<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;

class IncludeMethodWithAnnotationTest extends TestCase
{
    protected function setUp(): void
    {
        $this->setConfig([
            'enlighten.tests.ignore' => ['*']
        ]);

        parent::setUp();
    }

    #[Test]
    /**
     * @enlighten
     */
    function export_test_method_with_the_enlighten_annotation_even_if_its_ignored_in_the_config()
    {
        $this->assertExampleIsCreated();
    }

    #[Test]
    function it_does_not_export_test_method_ignored_with_the_config()
    {
        $this->assertExampleIsNotCreated();
    }
}
