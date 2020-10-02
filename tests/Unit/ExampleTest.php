<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Styde\Enlighten\Example;

class ExampleTest extends TestCase
{
    /** @test */
    function checks_if_the_related_test_passed()
    {
        $data = new Example(['test_status' => 'passed']);

        $this->assertTrue($data->passed);

        $data = new Example(['test_status' => 'failed']);

        $this->assertFalse($data->passed);
    }
}
