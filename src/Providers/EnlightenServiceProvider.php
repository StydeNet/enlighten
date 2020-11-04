<?php

namespace Styde\Enlighten\Providers;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Styde\Enlighten\Console\Commands\ExportDocumentationCommand;
use Styde\Enlighten\Console\Commands\FreshCommand;
use Styde\Enlighten\Console\Commands\MigrateCommand;
use Styde\Enlighten\Console\ContentRequest;
use Styde\Enlighten\Console\DocumentationExporter;
use Styde\Enlighten\Contracts\VersionControl;
use Styde\Enlighten\HttpExamples\HttpExampleCreatorMiddleware;
use Styde\Enlighten\HttpExamples\HttpExampleCreator;
use Styde\Enlighten\HttpExamples\RequestInspector;
use Styde\Enlighten\HttpExamples\ResponseInspector;
use Styde\Enlighten\HttpExamples\RouteInspector;
use Styde\Enlighten\HttpExamples\SessionInspector;
use Styde\Enlighten\TestInspector;
use Styde\Enlighten\TestRun;
use Styde\Enlighten\Utils\Annotations;
use Styde\Enlighten\Utils\Git;
use Styde\Enlighten\View\Components\AppLayoutComponent;
use Styde\Enlighten\View\Components\BreadcrumbsComponent;
use Styde\Enlighten\View\Components\CodeExampleComponent;
use Styde\Enlighten\View\Components\DynamicTabsComponent;
use Styde\Enlighten\View\Components\EditButtonComponent;
use Styde\Enlighten\View\Components\ExampleBreadcrumbs;
use Styde\Enlighten\View\Components\ExampleRequestsComponent;
use Styde\Enlighten\View\Components\ExceptionInfoComponent;
use Styde\Enlighten\View\Components\GroupBreadcrumbs;
use Styde\Enlighten\View\Components\HtmlResponseComponent;
use Styde\Enlighten\View\Components\KeyValueComponent;
use Styde\Enlighten\View\Components\RequestInfoComponent;
use Styde\Enlighten\View\Components\RequestInputTableComponent;
use Styde\Enlighten\View\Components\ResponseInfoComponent;
use Styde\Enlighten\View\Components\RouteParametersTableComponent;
use Styde\Enlighten\View\Components\SearchBoxComponent;
use Styde\Enlighten\View\Components\SearchBoxStaticComponent;
use Styde\Enlighten\View\Components\StatsBadgeComponent;
use Styde\Enlighten\View\Components\StatusBadgeComponent;

class EnlightenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom($this->packageRoot('config/enlighten.php'), 'enlighten');

        if (! $this->app['config']->get('enlighten.enabled')) {
            return;
        }

        $this->addDatabaseConnection($this->app['config']);

        $this->loadroutesFrom($this->packageRoot('src/Http/routes/web.php'));
        $this->loadroutesFrom($this->packageRoot('src/Http/routes/api.php'));

        $this->loadViewsFrom($this->packageRoot('resources/views'), 'enlighten');
        $this->loadTranslationsFrom($this->packageRoot('resources/lang'), 'enlighten');

        $this->registerViewComponents();

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom($this->packageRoot('database/migrations'));

            $this->registerPublishing();

            $this->registerCommands();
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
        $this->registerVersionControlSystem();
        $this->registerHttpExampleGenerator();
    }

    private function registerMiddleware()
    {
        $this->app[HttpKernel::class]->pushMiddleware(HttpExampleCreatorMiddleware::class);
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

    private function registerVersionControlSystem()
    {
        $this->app->singleton(VersionControl::class, Git::class);
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
            'breadcrumbs' => BreadcrumbsComponent::class,
            'search-box-static' => SearchBoxStaticComponent::class,
            'search-box' => SearchBoxComponent::class,
            // Group
            'code-example' => CodeExampleComponent::class,
            'content-table' => 'enlighten::components.content-table',
            'response-preview' => 'enlighten::components.response-preview',
            // Layout components
            'info-panel' => 'enlighten::components.info-panel',
            'scroll-to-top' => 'enlighten::components.scroll-to-top',
            'pre' => 'enlighten::components.pre',
            'main-layout' => 'enlighten::layout.main',
            'area-module-panel' => 'enlighten::components.area-module-panel',
            'queries-info' => 'enlighten::components.queries-info',
            'iframe' => 'enlighten::components.iframe',
            'widget' => 'enlighten::components.widget',
            'expansible-section' => 'enlighten::components.expansible-section',
            'svg-logo' => 'enlighten::components.svg-logo',
            'runs-table' => 'enlighten::components.runs-table',
            'panel-title' => 'enlighten::components.panel-title',
            'example-snippets' => 'enlighten::components.example-snippets',


            'example-requests' => ExampleRequestsComponent::class,
            'example-breadcrumbs' => ExampleBreadcrumbs::class,

            'group-breadcrumbs' => GroupBreadcrumbs::class

        ]);
    }

    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->packageRoot('config') => base_path('config'),
            ], 'enlighten-config');

            $this->publishes([
                $this->packageRoot('dist') => public_path('vendor/enlighten'),
                $this->packageRoot('/preview.png') => public_path('vendor/enlighten/img/preview.png'),
            ], 'enlighten-build');

            $this->publishes([
                $this->packageRoot('resources/views') => resource_path('views/vendor/enlighten'),
            ], 'enlighten-views');

            $this->publishes([
                $this->packageRoot('database/migrations') => base_path('database/migrations/enlighten'),
            ], 'enlighten-migrations');
        }
    }

    private function registerCommands(): void
    {
        $this->app->singleton(MigrateCommand::class, function ($app) {
            return new MigrateCommand($app['migrator'], $app['events']);
        });

        $this->app->singleton(ExportDocumentationCommand::class, function ($app) {
            return new ExportDocumentationCommand(
                new DocumentationExporter(
                    $app[Filesystem::class],
                    new ContentRequest($app[HttpKernel::class]),
                    $app['url']->to('/')
                )
            );
        });

        $this->commands([
            FreshCommand::class,
            MigrateCommand::class,
            ExportDocumentationCommand::class
        ]);
    }

    private function packageRoot(string $path): string
    {
        return __DIR__.'/../../'.$path;
    }
}
