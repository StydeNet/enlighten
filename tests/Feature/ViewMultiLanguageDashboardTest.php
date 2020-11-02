<?php

namespace Tests\Feature;

class ViewMultiLanguageDashboardTest extends TestCase
{
    /** @test */
    public function get_dashboard_view_with_translated_messages(): void
    {
        // Given
        $run = $this->createRun();

        // When set the english language...
        \App::setLocale('en');

        $response = $this->get(route('enlighten.area.show', ['run' => $run]));

        // Then
        $response->assertSee('There are no examples to show.');

        // When set the spanish language...
        \App::setLocale('es');

        $response = $this->get(route('enlighten.area.show', ['run' => $run]));

        // Then
        $response->assertSee('No hay ejemplos para mostrar.');
    }
}
