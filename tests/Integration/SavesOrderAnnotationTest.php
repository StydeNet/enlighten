<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\Example;

/**
 * @enlighten {"order": 9}
 */
class SavesOrderAnnotationTest extends TestCase
{
    #[Test]
    /**
     * @enlighten {"order": 22}
     */
    function it_saves_an_example_and_example_group_with_an_order_num_attribute()
    {
        $this->saveExampleStatus();

        $example = Example::first();

        $this->assertNotNull($example);

        $this->assertSame(9, $example->group->order_num);
        $this->assertSame(22, $example->order_num);
    }
}
