<?php

namespace Styde\Enlighten;

use Illuminate\Support\ServiceProvider;

class EnlightenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->mergeConfigFrom(__DIR__.'/../config/enlighten.php', 'enlighten');

        if ($this->app->environment('local', 'testing')) {
            $this->loadroutesFrom(__DIR__ . '/../routes/web.php');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'enlighten');

        $this->loadViewComponentsAs('enlighten', [
            'headline' => 'enlighten::components.headline',
            'html-response' => 'enlighten::components.html-response',
            'json-response' => 'enlighten::components.json-response',
            'key-value' => 'enlighten::components.key-value',
            'sub-title' => 'enlighten::components.sub-title',
        ]);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../dist' => public_path('enlighten'),
                __DIR__.'/../resources/views' => resource_path('views/vendor/enlighten'),
            ], 'enlighten');
        }
    }

    public function register()
    {
        $this->app->singleton(ExampleGenerator::class, function () {
            $config = $this->app->config->get('enlighten');

            return new ExampleGenerator(
                $config,
                new TestInspector,
                new RequestInspector(new RouteInspector),
                new ResponseInspector($config['response'])
            );
        });
    }
}
