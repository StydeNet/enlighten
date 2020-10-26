<?php

namespace Styde\Enlighten\Console\Commands;

use Illuminate\Database\Console\Migrations\FreshCommand as LaravelCommand;

class FreshCommand extends LaravelCommand
{
    protected $name = 'enlighten:migrate:fresh';

    protected $description = 'Drop all tables and re-run all the Enlighten migrations';

    public function handle()
    {
        $database = 'enlighten';

        $this->call('db:wipe', array_filter([
            '--database' => $database,
            '--drop-views' => $this->option('drop-views'),
            '--drop-types' => $this->option('drop-types'),
            '--force' => true,
        ]));

        $this->call('enlighten:migrate', array_filter([
            '--force' => true,
            '--step' => $this->option('step'),
        ]));

        return 0;
    }
}
