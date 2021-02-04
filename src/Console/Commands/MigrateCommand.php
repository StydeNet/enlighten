<?php

namespace Styde\Enlighten\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    protected $signature = 'enlighten:migrate
                {--force : Force the operation to run when in production}
                {--pretend : Dump the SQL queries that would be run}';

    protected $description = 'Run the Enlighten migrations.';

    public function handle(): void
    {
        $this->call('migrate', [
            '--database'  => 'enlighten',
            '--realpath' => true,
            '--path' =>  __DIR__ . '/../../../database/migrations',
            '--force' => $this->option('force'),
            '--pretend' => $this->option('pretend'),
        ]);
    }
}
