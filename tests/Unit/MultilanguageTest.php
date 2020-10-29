<?php

namespace Tests\Unit;

use Tests\TestCase;

class MultilanguageTest extends TestCase
{
    /** @test */
    public function loads_english_and_spanish_branch_commit_message_correctly()
    {
        \App::setLocale('en');
        
        $this->assertSame(
            __('enlighten::messages.branch_commit'),
            'Branch / Commit'
        );

        \App::setLocale('es');
        
        $this->assertSame(
            __('enlighten::messages.branch_commit'),
            'Rama / Confirmación'
        );
    }

    /** @test */
    public function loads_english_and_spanish_dashboard_message_correctly()
    {
        \App::setLocale('en');
        
        $this->assertSame(
            __('enlighten::messages.dashboard'),
            'Dashboard'
        );

        \App::setLocale('es');
        
        $this->assertSame(
            __('enlighten::messages.dashboard'),
            'Tablero'
        );
    }
}
