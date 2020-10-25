<?php

namespace Styde\Enlighten\Providers;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Styde\Enlighten\Http\Middleware\HttpExampleCreatorMiddleware;
use Styde\Enlighten\HttpExampleCreator;
use Styde\Enlighten\RequestInspector;
use Styde\Enlighten\ResponseInspector;
use Styde\Enlighten\RouteInspector;
use Styde\Enlighten\SessionInspector;
use Styde\Enlighten\TestInspector;
use Styde\Enlighten\TestRun;
use Styde\Enlighten\Utils\Annotations;
use Styde\Enlighten\Utils\TestTrace;
use Styde\Enlighten\View\Components\AppLayoutComponent;
use Styde\Enlighten\View\Components\CodeExampleComponent;
use Styde\Enlighten\View\Components\DynamicTabsComponent;
use Styde\Enlighten\View\Components\EditButtonComponent;
use Styde\Enlighten\View\Components\ExceptionInfoComponent;
use Styde\Enlighten\View\Components\HtmlResponseComponent;
use Styde\Enlighten\View\Components\KeyValueComponent;
use Styde\Enlighten\View\Components\RequestInfoComponent;
use Styde\Enlighten\View\Components\RequestInputTableComponent;
use Styde\Enlighten\View\Components\ResponseInfoComponent;
use Styde\Enlighten\View\Components\RouteParametersTableComponent;
use Styde\Enlighten\View\Components\StatsBadgeComponent;
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
        $this->loadroutesFrom($this->componentPath('routes/web.php'));
        $this->loadroutesFrom($this->componentPath('routes/api.php'));
        $this->loadViewsFrom($this->componentPath('resources/views'), 'enlighten');
        $this->registerViewComponents();

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom($this->componentPath('database/migrations'));
            $this->registerPublishing();
        }

        if ($this->app->runningUnitTests()) {
            $this->registerMiddleware();
        }
    }

    protected function addDatabaseConnection(Config $config)
    {
        if ($config->has('database.connections.enlighten')) {
            return;
        }

        $connection = $config->get('database.connections.'.$config->get('database.default'));

        $config->set('database.connections.enlighten', array_merge($connection, [
            'database' => $this->guessDatabaseName($connection),
        ]));
    }

    public function guessDatabaseName(array $connection)
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
            return TestRun::getInstance();
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

            return new TestInspector($app[TestRun::class], $annotations, $app['config']->get('enlighten.tests'));
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
            'dynamic-tabs' => DynamicTabsComponent::class,
            'exception-info' => ExceptionInfoComponent::class,
            'edit-button' => EditButtonComponent::class,
            // Group
            'code-example' => CodeExampleComponent::class,
            'content-table' => 'enlighten::group._content-table',
            'response-preview' => 'enlighten::group._response-preview',
            // Layout components
            'info-panel' => 'enlighten::components.info-panel',
            'scroll-to-top' => 'enlighten::components.scroll-to-top',
            'pre' => 'enlighten::components.pre',
            'main-layout' => 'enlighten::layout.main',
            'module-panel' => 'enlighten::dashboard._module-panel',
            'queries-info' => 'enlighten::components.queries-info',
            'iframe' => 'enlighten::components.iframe',

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
                $this->componentPath('/preview.png') => public_path('vendor/enlighten/img/preview.png'),
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
