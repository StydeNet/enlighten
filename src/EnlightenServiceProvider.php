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
            return new ExampleGenerator($this->app->config->get('enlighten'));
        });
    }
}
