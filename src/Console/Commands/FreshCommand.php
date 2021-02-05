<?php

namespace Styde\Enlighten\Console\Commands;

use Illuminate\Console\Command;

class FreshCommand extends Command
{
    protected $signature = 'enlighten:migrate:fresh
                {--force : Force the operation to run when in production}';

    protected $description = 'Drop all tables and re-run all the Enlighten migrations';

    public function handle(): void
    {
        $this->call('db:wipe', [
            '--database' => 'enlighten',
            '--force' => $this->option('force'),
        ]);

        $this->call('enlighten:migrate', [
            '--force' => $this->option('force'),
        ]);
    }
}
