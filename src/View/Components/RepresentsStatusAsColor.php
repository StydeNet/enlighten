<?php


namespace Styde\Enlighten\View\Components;

use Styde\Enlighten\Models\Statusable;

trait RepresentsStatusAsColor
{
    protected function getColor(Statusable $model)
    {
        return collect([
            'success' => 'green',
            'warning' => 'yellow',
            'failure' => 'red'
        ])->get($model->getStatus(), 'yellow');
    }
}
