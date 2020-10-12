<?php

namespace Styde\Enlighten\Tests;

use PHPUnit\Framework\TestFailure;
use PHPUnit\TextUI\DefaultResultPrinter;
use Styde\Enlighten\TestRun;

class ResultPrinter extends DefaultResultPrinter
{
    protected function printDefectTrace(TestFailure $defect): void
    {
        parent::printDefectTrace($defect);

        if ($link = TestRun::getFailedTestLink($defect->getTestName())) {
            $this->write("\n⚡See in Enlighten: ");
            $this->writeWithColor('fg-yellow, bold', "{$link}\n");
        }
    }
}
