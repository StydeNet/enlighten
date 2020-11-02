<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Enlighten;
use Tests\TestCase;

class GeneratesSlugFromMethodNameTest extends TestCase
{
    /** @test */
    function generates_slug_from_method_name()
    {
        $this->assertSame('it-list-users', Enlighten::generateSlugFromMethodName('it_list_users'));

        $this->assertSame('list-users', Enlighten::generateSlugFromMethodName('test_list_users'));

        $this->assertSame('create-users', Enlighten::generateSlugFromMethodName('TestCreateUsers'));

        $this->assertSame('show-users', Enlighten::generateSlugFromMethodName('showUsers'));
    }

    /** @test */
    function generates_slug_from_class_name_with_a_custom_generator()
    {
        Enlighten::setCustomSlugGenerator(function ($methodName, $from) {
            $this->assertSame('method', $from);

            return 'generated-slug';
        });

        $this->assertSame(
            'generated-slug',
            Enlighten::generateSlugFromMethodName('Anything')
        );
    }
}
