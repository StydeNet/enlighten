<?php

namespace Tests\Unit;

use Styde\Enlighten\Utils\Annotations;
use Tests\TestCase;

/**
 * @Class AnnotationsTest
 * @title Annotations Test
 * @package Tests\Unit
 */
class AnnotationsTest extends TestCase
{
    /** @test */
    function gets_annotations_from_class()
    {
        $annotations = Annotations::fromClass(AnnotationsTest::class);

        $this->assertInstanceOf(Annotations::class, $annotations);
        $this->assertCount(3, $annotations);
        $this->assertSame('AnnotationsTest', $annotations->get('Class'));
        $this->assertSame('Annotations Test', $annotations->get('title'));
        $this->assertSame('Tests\Unit', $annotations->get('package'));
    }

    /**
     * @test
     * @title Gets annotations from methods
     * @description It can get the annotation from a method.
     */
    function gets_annotations_from_methods()
    {
        $annotations = Annotations::fromMethod(AnnotationsTest::class, 'gets_annotations_from_methods');

        $this->assertInstanceOf(Annotations::class, $annotations);
        $this->assertCount(3, $annotations);

        $this->assertSame('', $annotations->get('test'));
        // Trailing spaces and dots are removed.
        $this->assertSame('Gets annotations from methods', $annotations->get('title'));
        $this->assertSame('It can get the annotation from a method', $annotations->get('description'));
    }
}
