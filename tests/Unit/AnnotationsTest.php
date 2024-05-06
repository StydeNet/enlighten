<?php

namespace Tests\Unit;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Utils\Annotations;
use Tests\TestCase;

/**
 * @Class AnnotationsTest
 * @title Annotations Test
 * @package Tests\Unit
 */
class AnnotationsTest extends TestCase
{
    #[Test]
    function gets_annotations_from_class(): void
    {
        $annotations = (new Annotations)->getFromClass(AnnotationsTest::class);

        $this->assertInstanceOf(Collection::class, $annotations);
        $this->assertCount(3, $annotations);
        $this->assertSame('AnnotationsTest', $annotations->get('Class'));
        $this->assertSame('Annotations Test', $annotations->get('title'));
        $this->assertSame('Tests\Unit', $annotations->get('package'));
    }

    #[Test]
    /**
     * @title Gets annotations from methods
     * @description It can get the annotation from a method.
     */
    function gets_annotations_from_methods(): void
    {
        $annotations = (new Annotations)->getFromMethod(AnnotationsTest::class, 'gets_annotations_from_methods');

        $this->assertInstanceOf(Collection::class, $annotations);
        $this->assertCount(2, $annotations);

        $this->assertSame('Gets annotations from methods', $annotations->get('title'));
        $this->assertSame('It can get the annotation from a method', $annotations->get('description'));
    }

    /** @enlighten {"order": 4} **/
    function test_gets_single_line_annotation(): void
    {
        $annotations = (new Annotations)->getFromMethod(AnnotationsTest::class, 'test_gets_single_line_annotation');

        $this->assertCount(1, $annotations);
        $this->assertSame('{"order": 4}', $annotations->get('enlighten'));
    }

    #[Test]
    /**
     * @title  it gets
     *        multiline
     *       annotations
     * @description It can get annotations
     * that span
     * across multiple lines
     */
    function gets_multiline_annotations(): void
    {
        $annotations = (new Annotations)->getFromMethod(AnnotationsTest::class, 'gets_multiline_annotations');

        $this->assertCount(2, $annotations);

        $expected = implode(PHP_EOL, [
            'it gets',
            'multiline',
            'annotations'
        ]);
        $this->assertSame($expected, $annotations->get('title'));

        $expected = implode(PHP_EOL, [
            'It can get annotations',
            'that span',
            'across multiple lines'
        ]);
        $this->assertSame($expected, $annotations->get('description'));
    }
}
