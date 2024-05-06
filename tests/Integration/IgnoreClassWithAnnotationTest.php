<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;

/**
 * @enlighten {"ignore": true}
 */
class IgnoreClassWithAnnotationTest extends TestCase
{
    #[Test]
    function does_not_export_test_classes_with_the_enlighten_ignore_annotation(): void
    {
        $this->assertExampleIsNotCreated();
    }
}
