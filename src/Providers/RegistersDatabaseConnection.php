<?php

namespace Styde\Enlighten\Providers;

use Illuminate\Config\Repository as Config;
use Illuminate\Support\Str;

trait RegistersDatabaseConnection
{
    protected function registerDatabaseConnection(Config $config): void
    {
        if ($config->has('database.connections.enlighten')) {
            return;
        }

        $connection = $config->get('database.connections.'.$config->get('database.default'));

        $config->set('database.connections.enlighten', array_merge($connection, [
            'database' => $this->guessDatabaseName($connection),
        ]));
    }

    protected function guessDatabaseName(array $connection)
    {
        if ($connection['driver'] === 'sqlite') {
            return $connection['database'];
        }

        $result = $connection['database'];

        if (Str::endsWith($result, '_tests')) {
            $result = Str::substr($result, 0, -6);
        } elseif (Str::endsWith($result, '_test')) {
            $result = Str::substr($result, 0, -5);
        }

        return "{$result}_enlighten";
    }
}
