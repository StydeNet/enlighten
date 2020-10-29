<?php

namespace Tests\Unit;

use Tests\TestCase;

class MultilanguageTest extends TestCase
{
    /** @test */
    public function it_has_an_english_branch_commit_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Branch / Commit', 'en'));
        $this->assertSame(
            __('enlighten::messages.Branch / Commit', [], 'en'),
            'Branch / Commit'
        );
    }

    /** @test */
    public function it_has_a_spanish_branch_commit_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Branch / Commit', 'es'));
        $this->assertSame(
            __('enlighten::messages.Branch / Commit', [], 'es'),
            'Rama / Confirmación'
        );
    }

    /** @test */
    public function it_has_an_english_dashboard_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Dashboard', 'en'));
        $this->assertSame(
            __('enlighten::messages.Dashboard', [], 'en'),
            'Dashboard'
        );
    }

    /** @test */
    public function it_has_a_spanish_dashboard_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Dashboard', 'es'));
        $this->assertSame(
            __('enlighten::messages.Dashboard', [], 'es'),
            'Tablero'
        );
    }

    /** @test */
    public function it_has_an_english_date_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Date', 'en'));
        $this->assertSame(
            __('enlighten::messages.Date', [], 'en'),
            'Date'
        );
    }

    /** @test */
    public function it_has_a_spanish_date_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Date', 'es'));
        $this->assertSame(
            __('enlighten::messages.Date', [], 'es'),
            'Fecha'
        );
    }

    /** @test */
    public function it_has_an_english_features_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Features', 'en'));
        $this->assertSame(
            __('enlighten::messages.Features', [], 'en'),
            'Features'
        );
    }

    /** @test */
    public function it_has_a_spanish_features_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Features', 'es'));
        $this->assertSame(
            __('enlighten::messages.Features', [], 'es'),
            'Funcionalidades'
        );
    }

    /** @test */
    public function it_has_an_english_input_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Input', 'en'));
        $this->assertSame(
            __('enlighten::messages.Input', [], 'en'),
            'Input'
        );
    }

    /** @test */
    public function it_has_a_spanish_input_message()
    {
        $this->assertTrue(\Lang::hasForLocale('enlighten::messages.Input', 'es'));
        $this->assertSame(
            __('enlighten::messages.Input', [], 'es'),
            'Entrada'
        );
    }
}
