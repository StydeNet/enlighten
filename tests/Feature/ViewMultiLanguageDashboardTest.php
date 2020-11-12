<?php

namespace Tests\Feature;

use App;

class ViewMultiLanguageDashboardTest extends TestCase
{
    /** @test */
    public function get_dashboard_view_with_translated_messages(): void
    {
        $run = $this->createRun();

        config(['enlighten.area_view' => 'modules']);

        App::setLocale('en');

        $this->get(route('enlighten.area.show', ['run' => $run]))
            ->assertSee('There are no examples to show.');

        App::setLocale('es');

        $this->get(route('enlighten.area.show', ['run' => $run]))
            ->assertSee('No hay ejemplos para mostrar.');
    }
}
