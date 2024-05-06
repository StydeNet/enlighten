<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Facades\Settings;
use Tests\TestCase;

class GeneratesSlugFromClassNameTest extends TestCase
{
    #[Test]
    function generates_slug_from_class_name(): void
    {
        $this->assertSame('feature-list-users', Settings::generateSlugFromClassName('Tests\Feature\ListUsersTest'));

        $this->assertSame('api-list-tests', Settings::generateSlugFromClassName('Tests\Api\ListTestsTest'));

        $this->assertSame('feature-admin-show-users', Settings::generateSlugFromClassName('Tests\Feature\Admin\ShowUsers'));

        $this->assertSame('unit-create-test', Settings::generateSlugFromClassName('Tests\Unit\CreateTestTest'));

        $this->assertSame(
            'modules-users-feature-admin-create-test',
            Settings::generateSlugFromClassName('Tests\Modules\Users\Feature\Admin\CreateTestTest')
        );
    }

    #[Test]
    function generates_slug_from_class_name_with_a_custom_generator(): void
    {
        Settings::setCustomSlugGenerator(function ($className, $from) {
            $this->assertSame('class', $from);

            return 'generated-slug';
        });

        $this->assertSame(
            'generated-slug',
            Settings::generateSlugFromClassName('CreateUserTest')
        );
    }
}
