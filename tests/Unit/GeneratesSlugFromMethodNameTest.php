<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Facades\Settings;
use Tests\TestCase;

class GeneratesSlugFromMethodNameTest extends TestCase
{
    #[Test]
    function generates_slug_from_method_name()
    {
        $this->assertSame('it-list-users', Settings::generateSlugFromMethodName('it_list_users'));

        $this->assertSame('list-users', Settings::generateSlugFromMethodName('test_list_users'));

        $this->assertSame('create-users', Settings::generateSlugFromMethodName('TestCreateUsers'));

        $this->assertSame('show-users', Settings::generateSlugFromMethodName('showUsers'));
    }

    #[Test]
    function generates_slug_from_class_name_with_a_custom_generator()
    {
        Settings::setCustomSlugGenerator(function ($methodName, $from) {
            $this->assertSame('method', $from);

            return 'generated-slug';
        });

        $this->assertSame(
            'generated-slug',
            Settings::generateSlugFromMethodName('Anything')
        );
    }
}
