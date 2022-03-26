<?php

namespace Styde\Enlighten\Providers;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Support\ServiceProvider;
use Styde\Enlighten\CodeExamples\CodeResultFormat;
use Styde\Enlighten\CodeExamples\HtmlResultFormat;
use Styde\Enlighten\Contracts\RunBuilder;
use Styde\Enlighten\Contracts\VersionControl;
use Styde\Enlighten\Drivers\ApiRunBuilder;
use Styde\Enlighten\Drivers\DatabaseRunBuilder;
use Styde\Enlighten\ExampleCreator;
use Styde\Enlighten\ExampleProfile;
use Styde\Enlighten\Exceptions\InvalidDriverException;
use Styde\Enlighten\HttpExamples\HttpExampleCreator;
use Styde\Enlighten\HttpExamples\HttpExampleCreatorMiddleware;
use Styde\Enlighten\HttpExamples\RequestInspector;
use Styde\Enlighten\HttpExamples\ResponseInspector;
use Styde\Enlighten\HttpExamples\RouteInspector;
use Styde\Enlighten\HttpExamples\SessionInspector;
use Styde\Enlighten\Settings;
use Styde\Enlighten\Utils\Annotations;
use Styde\Enlighten\Utils\Git;

class EnlightenServiceProvider extends ServiceProvider
{
    use RegistersConsoleConfiguration, RegistersDatabaseConnection;

    public function boot()
    {
        if ($this->app->environment('production') && ! $this->app->runningInConsole()) {
            return;
        }

        $this->mergeConfigFrom($this->packageRoot('config/enlighten.php'), 'enlighten');

        $this->registerDatabaseConnection($this->app['config']);


        if ($this->app->runningInConsole()) {
            $this->registerMiddleware();

            $this->registerPublishing();

            $this->registerCommands();
        }
    }

    public function register(): void
    {
        $this->registerSettings();
        $this->registerRunBuilder();
        $this->registerExampleCreator();
        $this->registerVersionControlSystem();
        $this->registerHttpExampleCreator();
        $this->registerCodeResultFormat();
    }

    private function registerMiddleware(): void
    {
        $this->app[HttpKernel::class]->pushMiddleware(HttpExampleCreatorMiddleware::class);
    }

    private function registerSettings(): void
    {
        $this->app->singleton(Settings::class, function () {
            return new Settings;
        });
    }

    private function registerRunBuilder(): void
    {
        $this->app->singleton(RunBuilder::class, function ($app) {
            return $this->getDriver($app);
        });
    }

    private function getDriver($app)
    {
        switch ($app['config']->get('enlighten.driver', 'database')) {
            case 'database':
                return new DatabaseRunBuilder;
            case 'api':
                return new ApiRunBuilder;
            default:
                throw new InvalidDriverException;
        }
    }

    private function registerExampleCreator(): void
    {
        $this->app->singleton(ExampleCreator::class, function ($app) {
            $annotations = new Annotations;

            $annotations->addCast('enlighten', function ($value) {
                $options = json_decode($value, JSON_OBJECT_AS_ARRAY);
                return array_merge(['include' => true], $options ?: []);
            });

            return new ExampleCreator(
                $app[RunBuilder::class],
                $annotations,
                $app[Settings::class],
                new ExampleProfile($app['config']->get('enlighten.tests')),
            );
        });
    }

    private function registerVersionControlSystem(): void
    {
        $this->app->singleton(VersionControl::class, Git::class);
    }

    private function registerHttpExampleCreator(): void
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

    private function registerCodeResultFormat(): void
    {
        $this->app->singleton(CodeResultFormat::class, HtmlResultFormat::class);
    }

    private function packageRoot(string $path): string
    {
        return __DIR__.'/../../'.$path;
    }
}
