<?php

namespace Styde\Enlighten\Tests;

use NunoMaduro\Collision\Adapters\Phpunit\Printer;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class CollisionResultPrinter extends Printer
{
    /**
     * Creates a new instance of the Style class.
     */
    protected function newStyle(ConsoleOutputInterface $output): CollisionStyleExtension
    {
        return new CollisionStyleExtension($output);
    }
}
