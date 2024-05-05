<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Styde\Enlighten\Enlighten;
use Styde\Enlighten\Exceptions\LaravelNotPresent;

// Deliberately extends from the PHPUnit\Framework\TestCase instead of our TestCase
class ChecksLaravelPresenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Enlighten::document();
    }

    #[Test]
    function throws_exception_when_calling_the_enlighten_helper_without_booting_laravel()
    {
        try {
            enlighten(function () {
            });
        } catch (LaravelNotPresent $exception) {
            $this->passed();
            return;
        }

        $this->fail('The exception LaravelNotPresent was not thrown');
    }

    #[Test]
    function throws_exception_when_calling_the_enlighten_test_facade_without_booting_laravel()
    {
        try {
            Enlighten::test(function () {
            });
        } catch (LaravelNotPresent $exception) {
            $this->passed();
            return;
        }

        $this->fail('The exception LaravelNotPresent was not thrown');
    }

    private function passed()
    {
        $this->assertTrue(true);
    }
}
