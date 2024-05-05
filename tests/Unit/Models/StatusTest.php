<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Styde\Enlighten\Models\Status;

class StatusTest extends TestCase
{
    #[Test]
    #[DataProvider('getStatusEquivalences')]
    function gets_a_simplified_status($testStatus, $expectedStatus)
    {
        $this->assertSame($expectedStatus, Status::fromTestStatus($testStatus));
    }

    public static function getStatusEquivalences(): array
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
}
