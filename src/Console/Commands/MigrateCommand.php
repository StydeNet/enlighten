<?php

namespace Styde\Enlighten\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateCommand as LaravelMigrateCommand;

class MigrateCommand extends LaravelMigrateCommand
{
    protected $signature = 'enlighten:migrate {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--path=* : The path(s) to the migrations files to be executed}
                {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                {--schema-path= : The path to a schema dump file}
                {--pretend : Dump the SQL queries that would be run}
                {--seed : Indicates if the seed task should be re-run}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    protected $description = 'Run the Enlighten migrations.';

    public function handle()
    {
        $this->input->setOption('database', 'enlighten');
        $this->input->setOption('path', base_path('database/migrations/enlighten'));
        $this->input->setOption('realpath', base_path('database/migrations/enlighten'));

        parent::handle();
    }
}
