<?php

namespace Styde\Enlighten\Providers;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Support\ServiceProvider;
use Styde\Enlighten\CodeExamples\CodeResultFormat;
use Styde\Enlighten\CodeExamples\HtmlResultFormat;
use Styde\Enlighten\Contracts\VersionControl;
use Styde\Enlighten\ExampleCreator;
use Styde\Enlighten\ExampleProfile;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\HttpExamples\HttpExampleCreator;
use Styde\Enlighten\HttpExamples\HttpExampleCreatorMiddleware;
use Styde\Enlighten\HttpExamples\RequestInspector;
use Styde\Enlighten\HttpExamples\ResponseInspector;
use Styde\Enlighten\HttpExamples\RouteInspector;
use Styde\Enlighten\HttpExamples\SessionInspector;
use Styde\Enlighten\Settings;
use Styde\Enlighten\TestRun;
use Styde\Enlighten\Utils\Annotations;
use Styde\Enlighten\Utils\Git;

class EnlightenServiceProvider extends ServiceProvider
{
    use RegistersConsoleConfiguration, RegistersViewComponents, RegistersDatabaseConnection;

    public function boot()
    {
        if ($this->app->environment('production') && ! $this->app->runningInConsole()) {
            return;
        }

        $this->mergeConfigFrom($this->packageRoot('config/enlighten.php'), 'enlighten');

        if (Enlighten::isDisabled()) {
            return;
        }

        $this->registerDatabaseConnection($this->app['config']);

        $this->loadroutesFrom($this->packageRoot('src/Http/routes/web.php'));
        $this->loadroutesFrom($this->packageRoot('src/Http/routes/api.php'));

        $this->loadViewsFrom($this->packageRoot('resources/views'), 'enlighten');

        $this->loadTranslationsFrom($this->packageRoot('resources/lang'), 'enlighten');

        $this->registerViewComponents();

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom($this->packageRoot('database/migrations'));

            $this->registerMiddleware();

            $this->registerPublishing();

            $this->registerCommands();
        }
    }

    public function register()
    {
        $this->registerSettings();
        $this->registerTestRun();
        $this->registerExampleCreator();
        $this->registerVersionControlSystem();
        $this->registerHttpExampleCreator();
        $this->registerCodeResultFormat();
    }

    private function registerMiddleware()
    {
        $this->app[HttpKernel::class]->pushMiddleware(HttpExampleCreatorMiddleware::class);
    }

    private function registerSettings()
    {
        $this->app->singleton(Settings::class, function () {
            return new Settings;
        });
    }

    private function registerTestRun()
    {
        $this->app->singleton(TestRun::class, function () {
            return TestRun::getInstance();
        });
    }

    private function registerExampleCreator()
    {
        $this->app->singleton(ExampleCreator::class, function ($app) {
            $annotations = new Annotations;

            $annotations->addCast('enlighten', function ($value) {
                $options = json_decode($value, JSON_OBJECT_AS_ARRAY);
                return array_merge(['include' => true], $options ?: []);
            });

            return new ExampleCreator(
                $app[TestRun::class],
                $annotations,
                $app[Settings::class],
                new ExampleProfile($app['config']->get('enlighten.tests')),
            );
        });
    }

    private function registerVersionControlSystem()
    {
        $this->app->singleton(VersionControl::class, Git::class);
    }

    private function registerHttpExampleCreator()
    {
        $this->app->singleton(HttpExampleCreator::class, function ($app) {
            return new HttpExampleCreator(
                $app[ExampleCreator::class],
                new RequestInspector,
                new RouteInspector,
                new ResponseInspector,
                new SessionInspector($app['session.store']),
            );
        });
    }

    private function registerCodeResultFormat()
    {
        $this->app->singleton(CodeResultFormat::class, HtmlResultFormat::class);
    }

    private function packageRoot(string $path): string
    {
        return __DIR__.'/../../'.$path;
    }
}
