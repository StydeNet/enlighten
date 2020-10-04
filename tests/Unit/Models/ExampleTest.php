<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;

class ExampleTest extends \Orchestra\Testbench\TestCase
{
    /** @test */
    function gets_the_path_to_the_file()
    {
        $example = new Example;
        $example->group = new ExampleGroup([
            'class_name' => 'Tests\Feature\Admin\CreateUsersTest',
        ]);

        $this->assertSame(1, preg_match('@phpstorm://open\?file=(.*?)Tests%2FFeature%2FAdmin%2FCreateUsersTest.php@', $example->file_link));
    }

    /** @test */
    function checks_if_the_related_test_passed()
    {
        $data = new Example(['test_status' => 'passed']);

        $this->assertTrue($data->passed);

        $data = new Example(['test_status' => 'failed']);

        $this->assertFalse($data->passed);
    }

    /** @test */
    function checks_if_the_related_test_failed()
    {
        $data = new Example(['test_status' => 'failure']);

        $this->assertTrue($data->failed);

        $data = new Example(['test_status' => 'passed']);

        $this->assertFalse($data->failed);

        $data = new Example(['test_status' => 'error']);

        $this->assertTrue($data->failed);
    }
}
