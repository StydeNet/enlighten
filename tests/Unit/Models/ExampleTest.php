<?php

namespace Tests\Unit\Models;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    function gets_the_path_to_the_file()
    {
        $example = new Example([
            'line' => 3,
        ]);
        $example->group = new ExampleGroup([
            'class_name' => 'Tests\Feature\Admin\CreateUsersTest',
        ]);

        $this->assertSame(1, preg_match('@phpstorm://open\?file=(.*?)Tests%2FFeature%2FAdmin%2FCreateUsersTest.php&line=3@', $example->file_link));
    }

    /**
     * @test
     * @dataProvider getStatusEquivalences
     */
    function gets_a_simplified_status($testStatus, $expectedStatus)
    {
        $data = new Example(['test_status' => $testStatus]);

        $this->assertSame($expectedStatus, $data->getStatus());
    }

    public function getStatusEquivalences(): array
    {
        return [
            ['passed', 'success'],
            ['warning', 'warning'],
            ['risky', 'warning'],
            ['incomplete', 'warning'],
            ['skipped', 'warning'],
            ['error', 'failure'],
            ['failure', 'failure'],
        ];
    }

    /** @test */
    function get_the_example_url()
    {
        $exampleGroup = new ExampleGroup([
            'id' => 1,
            'run_id' => 1,
            'class_name' => 'Tests\Feature\ApiRequestTest',
        ]);

        $example = new Example([
            'group' => $exampleGroup,
            'method_name' => 'test_list_users'
        ]);

        $this->assertSame('http://localhost/enlighten/run/1/feature/1#test_list_users', $example->url);
    }
}
