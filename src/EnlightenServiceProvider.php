<?php

namespace Styde\Enlighten;

use Illuminate\Support\ServiceProvider;

class EnlightenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->mergeConfigFrom(__DIR__.'/../config/enlighten.php', 'enlighten');
    }

    public function register()
    {
        $this->app->bind(ExampleGenerator::class, function () {
            $config = $this->app->config->get('enlighten');

            return new ExampleGenerator($config['examples']['directory']);
        });
    }
}
