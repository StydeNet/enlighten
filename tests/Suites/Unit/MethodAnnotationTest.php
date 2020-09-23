<?php

namespace Tests\Suites\Unit;

use Styde\Enlighten\Example;
use Tests\TestCase;

class MethodAnnotationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->config->set([
            'enlighten.exclude' => [
                'does_not_export_test_methods_excluded_in_the_configuration',
            ],
        ]);
    }

    /** @test */
    function does_not_export_test_methods_excluded_in_the_configuration()
    {
        $this->assertTestIsNotExportedAsExample();
    }

    /**
     * @test
     * @enlighten {"exclude": true}
     */
    function does_not_export_test_methods_with_the_enlighten_exclude_annotation()
    {
        $this->assertTestIsNotExportedAsExample();
    }

    protected function assertTestIsNotExportedAsExample()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('user', [
            'name' => 'Duilio',
            'email' => 'duilio@example.test',
            'password' => 'my-password',
        ]);

        $response->assertRedirect('/');

        $this->assertSame(0, Example::count());
    }
}
