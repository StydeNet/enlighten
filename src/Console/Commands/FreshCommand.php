<?php

namespace Styde\Enlighten\Console\Commands;

use Illuminate\Console\Command;

class FreshCommand extends Command
{
    protected $name = 'enlighten:migrate:fresh';

    protected $description = 'Drop all tables and re-run all the Enlighten migrations';

    public function handle()
    {
        $database = 'enlighten';

        $this->call('db:wipe', array_filter([
            '--database' => $database,
            '--drop-views' => $this->hasOption('drop-views') ? $this->option('drop-views') : null,
            '--drop-types' => $this->hasOption('drop-types') ? $this->option('drop-types') : null,
            '--force' => true,
        ]));

        $this->call('enlighten:migrate', array_filter([
            '--force' => true,
            '--step' => $this->hasOption('step') ? $this->option('step') : null,
        ]));

        return 0;
    }
}
