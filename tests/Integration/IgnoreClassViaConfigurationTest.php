<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;

class IgnoreClassViaConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        $this->setConfig([
            'enlighten.tests.ignore' => [
                '*IgnoreClass*',
            ],
        ]);

        parent::setUp();
    }

    #[Test]
    function does_not_export_test_classes_ignored_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }
}
