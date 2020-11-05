<?php

namespace Styde\Enlighten\Tests;

use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\TestResult;
use PHPUnit\TextUI\DefaultResultPrinter;
use Styde\Enlighten\TestRun;

class BasicResultPrinter extends DefaultResultPrinter
{
    const BASIC_FORMAT = 'fg-white, bg-black, bold';
    const HIGHLIGHT = 'fg-yellow, bg-black, bold';

    protected function printDefectTrace(TestFailure $defect): void
    {
        parent::printDefectTrace($defect);

        $link = TestRun::getInstance()->getFailedTestLink($defect->getTestName());

        if ($link) {
            $this->printSeeInEnlightenLink($link);
        }
    }

    protected function printFooter(TestResult $result): void
    {
        parent::printFooter($result);

        if (TestRun::getInstance()->missingSetup()) {
            $this->printMissingSetupWarning();
        }
    }

    protected function printSeeInEnlightenLink(string $link): void
    {
        $this->writeNewLine();

        $this->writeWithColor(self::BASIC_FORMAT, '💡️ See in Enlighten:', false);
        $this->writeWithColor(self::HIGHLIGHT, " {$link}");

        $this->writeNewLine();
    }

    private function printMissingSetupWarning()
    {
        $this->writeNewLine();

        $this->writeWithColor(self::BASIC_FORMAT, 'It seems Enlighten is installed and enabled but you forgot to call:');

        $this->writeWithColor(self::HIGHLIGHT, '`$this->setUpEnlighten();`', false);
        $this->writeWithColor(self::BASIC_FORMAT, ' in one or more of your Feature tests.');

        $this->writeWithColor(self::BASIC_FORMAT, 'Learn how: ', false);
        $this->writeWithColor(self::HIGHLIGHT, 'https://github.com/StydeNet/enlighten#installation');
    }
}
