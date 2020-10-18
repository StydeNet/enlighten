<?php

namespace Tests\Feature\Widgets;

use Illuminate\Contracts\Support\Responsable;
use Tests\Feature\TestCase;

class WidgetControllerTest extends TestCase
{
    /** @test */
    public function load_widgets_dynamically_using_the_container(): void
    {
        $this->markTestIncomplete();

        $response = $this->get('/enlighten/widget/my-custom-widget');

        $response->assertOk()
            ->assertSee('my custom widget');
    }

    /** @test */
    public function load_widgets_dynamically_using_widget_classes(): void
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function load_widgets_dynamically_using_anonymous_widgets(): void
    {
        $this->markTestIncomplete();
    }

}
