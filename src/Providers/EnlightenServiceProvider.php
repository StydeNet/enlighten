<?php

namespace Styde\Enlighten\Providers;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Styde\Enlighten\Http\Middleware\HttpExampleGeneratorMiddleware;
use Styde\Enlighten\HttpExampleGenerator;
use Styde\Enlighten\RequestInspector;
use Styde\Enlighten\ResponseInspector;
use Styde\Enlighten\RouteInspector;
use Styde\Enlighten\SessionInspector;
use Styde\Enlighten\TestInspector;
use Styde\Enlighten\TestRun;
use Styde\Enlighten\View\Components\ResponseInfoComponent;
use Styde\Enlighten\View\Components\StatusBadgeComponent;

class EnlightenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom($this->componentPath('config/enlighten.php'), 'enlighten');

        if (! $this->app['config']->get('enlighten.enabled')) {
            return;
        }

        $this->addDatabaseConnection($this->app['config']);

        $this->loadMigrationsFrom($this->componentPath('database/migrations'));

        $this->registerMiddleware();

        $this->loadroutesFrom($this->componentPath('routes/web.php'));

        $this->loadViewsFrom($this->componentPath('resources/views'), 'enlighten');

        $this->registerViewComponents();

        $this->registerPublishing();
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

    private function registerMiddleware()
    {
        $this->app[Kernel::class]->pushMiddleware(HttpExampleGeneratorMiddleware::class);
    }

    private function registerTestInspector()
    {
        $this->app->singleton(TestInspector::class, function () {
            return new TestInspector(TestRun::getInstance(), $this->app['config']->get('enlighten.tests'));
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

    private function registerViewComponents(): void
    {
        $this->loadViewComponentsAs('enlighten', [
            'status-badge' => StatusBadgeComponent::class,
            'response-info' => ResponseInfoComponent::class,
            'html-response' => 'enlighten::components.html-response',
            'json-response' => 'enlighten::components.json-response',
            'key-value' => 'enlighten::components.key-value',
            'info-panel' => 'enlighten::components.info-panel',
            'app-layout' => 'enlighten::components.app-layout',
            'scroll-to-top' => 'enlighten::components.scroll-to-top',
            'request-info' => 'enlighten::components.request-info',
            'parameters-table' => 'enlighten::components.parameters-table',
            'request-input-table' => 'enlighten::components.request-input-table',
        ]);
    }

    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->componentPath('config') => base_path('config'),
            ], 'enlighten-config');

            $this->publishes([
                $this->componentPath('dist') => public_path('vendor/enlighten'),
            ], 'enlighten-build');

            $this->publishes([
                $this->componentPath('resources/views') => resource_path('views/vendor/enlighten'),
            ], 'enlighten-views');
        }
    }

    private function componentPath(string $path)
    {
        return __DIR__.'/../../'.$path;
    }
}
