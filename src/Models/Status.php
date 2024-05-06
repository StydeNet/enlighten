<?php

namespace Styde\Enlighten\Models;

class Status
{
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const FAILURE = 'failure';
    const UNKNOWN = 'unkown';

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
