<?php

namespace Tests\Unit;

use Tests\TestCase;

class MultilanguageTest extends TestCase
{
    /** @test */
    public function it_has_an_english_branch_commit_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.branch_commit', 'en'));
        $this->assertSame(
            __('enlighten::messages.branch_commit', [], 'en'),
            'Branch / Commit'
        );
    }

    /** @test */
    public function it_has_a_spanish_branch_commit_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.branch_commit', 'es'));
        $this->assertSame(
            __('enlighten::messages.branch_commit', [], 'es'),
            'Rama / Confirmación'
        );
    }

    /** @test */
    public function it_has_an_english_dashboard_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.dashboard', 'en'));
        $this->assertSame(
            __('enlighten::messages.dashboard', [], 'en'),
            'Dashboard'
        );
    }

    /** @test */
    public function it_has_a_spanish_dashboard_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.dashboard', 'es'));
        $this->assertSame(
            __('enlighten::messages.dashboard', [], 'es'),
            'Tablero'
        );
    }

    /** @test */
    public function it_has_an_english_date_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.date', 'en'));
        $this->assertSame(
            __('enlighten::messages.date', [], 'en'),
            'Date'
        );
    }

    /** @test */
    public function it_has_a_spanish_date_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.date', 'es'));
        $this->assertSame(
            __('enlighten::messages.date', [], 'es'),
            'Fecha'
        );
    }

    /** @test */
    public function it_has_an_english_features_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.features', 'en'));
        $this->assertSame(
            __('enlighten::messages.features', [], 'en'),
            'Features'
        );
    }

    /** @test */
    public function it_has_a_spanish_features_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.features', 'es'));
        $this->assertSame(
            __('enlighten::messages.features', [], 'es'),
            'Funcionalidades'
        );
    }

    /** @test */
    public function it_has_an_english_input_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.input', 'en'));
        $this->assertSame(
            __('enlighten::messages.input', [], 'en'),
            'Input'
        );
    }

    /** @test */
    public function it_has_a_spanish_input_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.input', 'es'));
        $this->assertSame(
            __('enlighten::messages.input', [], 'es'),
            'Entrada'
        );
    }


    /** @test */
    public function it_has_an_english_output_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.output', 'en'));
        $this->assertSame(
            __('enlighten::messages.output', [], 'en'),
            'Output'
        );
    }

    /** @test */
    public function it_has_a_spanish_output_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.output', 'es'));
        $this->assertSame(
            __('enlighten::messages.output', [], 'es'),
            'Salida'
        );
    }

    /** @test */
    public function it_has_an_english_pattern_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.pattern', 'en'));
        $this->assertSame(
            __('enlighten::messages.pattern', [], 'en'),
            'Pattern'
        );
    }

    /** @test */
    public function it_has_a_spanish_pattern_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.pattern', 'es'));
        $this->assertSame(
            __('enlighten::messages.pattern', [], 'es'),
            'Patrón'
        );
    }

    /** @test */
    public function it_has_an_english_request_queries_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.request_queries', 'en'));
        $this->assertSame(
            __('enlighten::messages.request_queries', [], 'en'),
            'Request Queries'
        );
    }

    /** @test */
    public function it_has_a_spanish_request_queries_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.request_queries', 'es'));
        $this->assertSame(
            __('enlighten::messages.request_queries', [], 'es'),
            'Consultas de petición'
        );
    }

    /** @test */
    public function it_has_an_english_requirement_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.requirement', 'en'));
        $this->assertSame(
            __('enlighten::messages.requirement', [], 'en'),
            'Requirement'
        );
    }

    /** @test */
    public function it_has_a_spanish_requirement_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.requirement', 'es'));
        $this->assertSame(
            __('enlighten::messages.requirement', [], 'es'),
            'Requerimiento'
        );
    }

    /** @test */
    public function it_has_an_english_response_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.response', 'en'));
        $this->assertSame(
            __('enlighten::messages.response', [], 'en'),
            'Response'
        );
    }

    /** @test */
    public function it_has_a_spanish_response_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.response', 'es'));
        $this->assertSame(
            __('enlighten::messages.response', [], 'es'),
            'Respuesta'
        );
    }

    /** @test */
    public function it_has_an_english_route_parameter_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.route_parameter', 'en'));
        $this->assertSame(
            __('enlighten::messages.route_parameter', [], 'en'),
            'Route Parameter'
        );
    }

    /** @test */
    public function it_has_a_spanish_route_parameter_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.route_parameter', 'es'));
        $this->assertSame(
            __('enlighten::messages.route_parameter', [], 'es'),
            'Parámetro de ruta'
        );
    }

    /** @test */
    public function it_has_an_english_session_data_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.session_data', 'en'));
        $this->assertSame(
            __('enlighten::messages.session_data', [], 'en'),
            'Session Data'
        );
    }

    /** @test */
    public function it_has_a_spanish_session_data_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.session_data', 'es'));
        $this->assertSame(
            __('enlighten::messages.session_data', [], 'es'),
            'Datos de sesión'
        );
    }

    /** @test */
    public function it_has_an_english_snippet_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.snippet', 'en'));
        $this->assertSame(
            __('enlighten::messages.snippet', [], 'en'),
            'Snippet'
        );
    }

    /** @test */
    public function it_has_a_spanish_snippet_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.snippet', 'es'));
        $this->assertSame(
            __('enlighten::messages.snippet', [], 'es'),
            'Snippet'
        );
    }

    /** @test */
    public function it_has_an_english_stats_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.stats', 'en'));
        $this->assertSame(
            __('enlighten::messages.stats', [], 'en'),
            'Stats'
        );
    }

    /** @test */
    public function it_has_a_spanish_stats_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.stats', 'es'));
        $this->assertSame(
            __('enlighten::messages.stats', [], 'es'),
            'Estadísticas'
        );
    }

    /** @test */
    public function it_has_an_english_test_queries_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.test_queries', 'en'));
        $this->assertSame(
            __('enlighten::messages.test_queries', [], 'en'),
            'Test Queries'
        );
    }

    /** @test */
    public function it_has_a_spanish_test_queries_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.test_queries', 'es'));
        $this->assertSame(
            __('enlighten::messages.test_queries', [], 'es'),
            'Consultas de prueba'
        );
    }

    /** @test */
    public function it_has_an_english_there_are_no_examples_to_show_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.there_are_no_examples_to_show', 'en'));
        $this->assertSame(
            __('enlighten::messages.there_are_no_examples_to_show', [], 'en'),
            'There are no examples to show.'
        );
    }

    /** @test */
    public function it_has_a_spanish_there_are_no_examples_to_show_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.there_are_no_examples_to_show', 'es'));
        $this->assertSame(
            __('enlighten::messages.there_are_no_examples_to_show', [], 'es'),
            'No hay ejemplos para mostrar.'
        );
    }

    /** @test */
    public function it_has_an_english_time_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.time', 'en'));
        $this->assertSame(
            __('enlighten::messages.time', [], 'en'),
            'Time'
        );
    }

    /** @test */
    public function it_has_a_spanish_time_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.time', 'es'));
        $this->assertSame(
            __('enlighten::messages.time', [], 'es'),
            'Hora'
        );
    }

    /** @test */
    public function it_has_an_english_value_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.value', 'en'));
        $this->assertSame(
            __('enlighten::messages.value', [], 'en'),
            'Value'
        );
    }

    /** @test */
    public function it_has_a_spanish_value_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.value', 'es'));
        $this->assertSame(
            __('enlighten::messages.value', [], 'es'),
            'Valor'
        );
    }

    /** @test */
    public function it_has_an_english_view_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.view', 'en'));
        $this->assertSame(
            __('enlighten::messages.view', [], 'en'),
            'View'
        );
    }

    /** @test */
    public function it_has_a_spanish_view_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.view', 'es'));
        $this->assertSame(
            __('enlighten::messages.view', [], 'es'),
            'Vista'
        );
    }
}
