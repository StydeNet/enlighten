<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewDashboardMultilanguageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_dashboard_view_with_translated_messages(): void
    {
        // Given
        $run = $this->createRun();

        // When set the english language...
        \App::setLocale('en');

        $response = $this->get(route('enlighten.run.show', ['run' => $run]));

        // Then
        $response->assertSee('Dashboard')
            ->assertSee('There are no examples to show.');

        // When set the spanish language...
        \App::setLocale('es');

        $response = $this->get(route('enlighten.run.show', ['run' => $run]));

        // Then
        $response->assertSee('Tablero')
            ->assertSee('No hay ejemplos para mostrar.');
    }
}
