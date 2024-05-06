<?php

namespace Styde\Enlighten\Models;

class Status
{
    public const SUCCESS = 'success';
    public const WARNING = 'warning';
    public const FAILURE = 'failure';
    public const UNKNOWN = 'unkown';

    public static function fromTestStatus($testStatus)
    {
        if (in_array($testStatus, ['passed', 'success'])) {
            return Status::SUCCESS;
        }

        if (in_array($testStatus, ['failure', 'error'])) {
            return Status::FAILURE;
        }

        if (in_array($testStatus, ['skipped', 'incomplete', 'risky', 'warning'])) {
            return Status::WARNING;
        }

        return Status::UNKNOWN;
    }
}
