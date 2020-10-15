<?php

namespace Styde\Enlighten\Tests;

use PHPUnit\Framework\TestFailure;
use PHPUnit\TextUI\DefaultResultPrinter;
use Styde\Enlighten\TestRun;

class BasicResultPrinter extends DefaultResultPrinter
{
    protected function printDefectTrace(TestFailure $defect): void
    {
        parent::printDefectTrace($defect);

        $link = TestRun::getInstance()->getFailedTestLink($defect->getTestName());

        if ($link) {
            $this->writeWithColor('fg-white, bg-black, bold', "\n 💡️ See in Enlighten:", false);
            $this->writeWithColor('fg-yellow, bg-black', " {$link} \n");
        }
    }
}
