<?php

namespace Tests\Unit;

use Illuminate\Support\Str;
use Styde\Enlighten\Facades\Enlighten;
use Tests\TestCase;

class GeneratesSlugFromClassNameTest extends TestCase
{
    /** @test */
    function generates_slug_from_class_name()
    {
        $this->assertSame('feature-list-users', Enlighten::generateSlugFromClassName('Tests\Feature\ListUsersTest'));

        $this->assertSame('api-list-tests', Enlighten::generateSlugFromClassName('Tests\Api\ListTestsTest'));

        $this->assertSame('feature-admin-show-users', Enlighten::generateSlugFromClassName('Tests\Feature\Admin\ShowUsers'));

        $this->assertSame('unit-create-test', Enlighten::generateSlugFromClassName('Tests\Unit\CreateTestTest'));

        $this->assertSame(
            'modules-users-feature-admin-create-test',
            Enlighten::generateSlugFromClassName('Tests\Modules\Users\Feature\Admin\CreateTestTest')
        );
    }

    /** @test */
    function generates_slug_from_class_name_with_a_custom_generator()
    {
        Enlighten::setCustomSlugGenerator(function ($className, $from) {
            $this->assertSame('class', $from);

            return 'generated-slug';
        });

        $this->assertSame(
            'generated-slug',
            Enlighten::generateSlugFromClassName('CreateUserTest')
        );
    }
}
