<?php

namespace Styde\Enlighten\Providers;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Styde\Enlighten\Utils\Annotations;
use Styde\Enlighten\Utils\GitInfo;
use Styde\Enlighten\Http\Middleware\HttpExampleCreatorMiddleware;
use Styde\Enlighten\HttpExampleCreator;
use Styde\Enlighten\RequestInspector;
use Styde\Enlighten\ResponseInspector;
use Styde\Enlighten\RouteInspector;
use Styde\Enlighten\SessionInspector;
use Styde\Enlighten\TestInspector;
use Styde\Enlighten\TestRun;
use Styde\Enlighten\Utils\TestTrace;
use Styde\Enlighten\View\Components\AppLayoutComponent;
use Styde\Enlighten\View\Components\CodeExampleComponent;
use Styde\Enlighten\View\Components\HtmlResponseComponent;
use Styde\Enlighten\View\Components\JsonResponseComponent;
use Styde\Enlighten\View\Components\KeyValueComponent;
use Styde\Enlighten\View\Components\RequestInputTableComponent;
use Styde\Enlighten\View\Components\RouteParametersTableComponent;
use Styde\Enlighten\View\Components\RequestInfoComponent;
use Styde\Enlighten\View\Components\ResponseInfoComponent;
use Styde\Enlighten\View\Components\StatsBadgeComponent;
use Styde\Enlighten\View\Components\StatusBadgeComponent;

class EnlightenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom($this->componentPath('config/enlighten.php'), 'enlighten');

        if (!$this->app['config']->get('enlighten.enabled')) {
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
        $this->registerTestRun();
        $this->registerTestInspector();
        $this->registerHttpExampleGenerator();
    }

    private function registerMiddleware()
    {
        $this->app[Kernel::class]->pushMiddleware(HttpExampleCreatorMiddleware::class);
    }

    private function registerTestRun()
    {
        $this->app->singleton(TestRun::class, function () {
            return new TestRun(new GitInfo);
        });
    }

    private function registerTestInspector()
    {
        $this->app->singleton(TestInspector::class, function ($app) {
            $annotations = new Annotations;

            $annotations->addCast('enlighten', function ($value) {
                $options = json_decode($value, JSON_OBJECT_AS_ARRAY);
                return array_merge(['include' => true], $options ?: []);
            });

            return new TestInspector(
                $app[TestRun::class], new TestTrace, $annotations,
                $app['config']->get('enlighten.tests')
            );
        });
    }

    private function registerHttpExampleGenerator()
    {
        $this->app->singleton(HttpExampleCreator::class, function ($app) {
            return new HttpExampleCreator(
                $app[TestInspector::class],
                new RequestInspector,
                new RouteInspector,
                new ResponseInspector,
                new SessionInspector($app['session.store']),
            );
        });
    }

    private function registerViewComponents(): void
    {
        $this->loadViewComponentsAs('enlighten', [
            'status-badge' => StatusBadgeComponent::class,
            'response-info' => ResponseInfoComponent::class,
            'request-info' => RequestInfoComponent::class,
            'stats-badge' => StatsBadgeComponent::class,
            'html-response' => HtmlResponseComponent::class,
            'key-value' => KeyValueComponent::class,
            'app-layout' => AppLayoutComponent::class,
            'route-parameters-table' => RouteParametersTableComponent::class,
            'request-input-table' => RequestInputTableComponent::class,

            // group
            'code-example' => CodeExampleComponent::class,
            'content-table' => 'enlighten::group._content-table',
            'response-preview' => 'enlighten::group._response-preview',

            // layout components
            'info-panel' => 'enlighten::components.info-panel',
            'scroll-to-top' => 'enlighten::components.scroll-to-top',
            'pre' => 'enlighten::components.pre',
            'main-layout' => 'enlighten::layout.main',
            'module-panel' => 'enlighten::dashboard._module-panel'
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
