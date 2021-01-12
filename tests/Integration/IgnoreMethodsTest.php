<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;

class IgnoreMethodsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->setConfig([
            'enlighten.tests.ignore' => [
                'does_not_export_test_methods_ignored_in_the_configuration',
                '*use_wildcards_to_ignore*',
            ],
        ]);

        parent::setUp();
    }

    /** @test */
    function does_not_export_test_methods_ignored_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }

    /** @test */
    function can_use_wildcards_to_ignore_a_test_method_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function does_not_export_test_methods_with_the_enlighten_ignore_annotation()
    {
        $this->assertExampleIsNotCreated();
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function can_ignore_test_methods_that_throw_an_http_exception()
    {
        $response = $this->post('not-found', [
            'name' => 'Duilio',
            'email' => 'duilio@example.test',
            'password' => 'my-password',
        ]);

        $response->assertNotFound();

        $this->assertSame(0, Example::count(), 'An unexpected example was created.');
    }
}
