<?php

namespace Styde\Enlighten;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Styde\Enlighten\View\ResponseInfoComponent;
use Styde\Enlighten\View\StatusBadgeComponent;

class EnlightenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->addDatabaseConnection($this->app->config);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->mergeConfigFrom(__DIR__.'/../config/enlighten.php', 'enlighten');

        $this->registerHttpExampleGeneratorMiddleware();

        if ($this->app->environment('local', 'testing')) {
            $this->loadroutesFrom(__DIR__ . '/../routes/web.php');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'enlighten');

        $this->loadViewComponentsAs('enlighten', [
            'html-response' => 'enlighten::components.html-response',
            'json-response' => 'enlighten::components.json-response',
            'key-value' => 'enlighten::components.key-value',
            'info-panel' => 'enlighten::components.info-panel',
            'app-layout' => 'enlighten::components.app-layout',
            'scroll-to-top' => 'enlighten::components.scroll-to-top',
            'status-badge' => StatusBadgeComponent::class,
            'response-info' => ResponseInfoComponent::class
        ]);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config' => base_path('config'),
            ], 'enlighten-config');

            $this->publishes([
                __DIR__.'/../dist' => public_path('vendor/enlighten'),
            ], 'enlighten-build');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/enlighten'),
            ], 'enlighten-views');
        }
    }

    protected function addDatabaseConnection(Config $config)
    {
        if ($config->has('database.connections.enlighten')) {
            return;
        }

        $connection = $config->get('database.connections.'.$config->get('database.default'));

        if ($connection['driver'] !== 'sqlite') {
            $connection['database'] = $connection['database'].'_enlighten';
        }

        $config->set('database.connections.enlighten', $connection);
    }

    public function register()
    {
        $this->registerTestInspector();
        $this->registerHttpExampleGenerator();
    }

    private function registerHttpExampleGeneratorMiddleware()
    {
        if ($this->app->config->get('enlighten.enable')) {
            $this->app[Kernel::class]->pushMiddleware(HttpExampleGeneratorMiddleware::class);
        }
    }

    private function registerTestInspector()
    {
        $this->app->singleton(TestInspector::class, function () {
            return new TestInspector($this->app['config']->get('enlighten.tests'));
        });
    }

    private function registerHttpExampleGenerator()
    {
        $this->app->singleton(HttpExampleGenerator::class, function () {
            $config = $this->app['config']->get('enlighten');

            return new HttpExampleGenerator(
                $this->app[TestInspector::class],
                new RequestInspector(new RouteInspector, $config['request']),
                new ResponseInspector($config['response']),
                new SessionInspector($this->app['session.store']),
            );
        });
    }
}
