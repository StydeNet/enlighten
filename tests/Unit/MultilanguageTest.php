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
}
