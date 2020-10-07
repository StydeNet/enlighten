<?php


namespace Styde\Enlighten\View\Components;

use Styde\Enlighten\Statusable;

trait RepresentsStatusAsColor
{
    protected function getColor(Statusable $model)
    {
        if ($model->hasPassed()) {
            return 'green';
        } elseif ($model->hasFailed()) {
            return 'red';
        } else {
            return 'yellow';
        }
    }
}
