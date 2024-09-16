<?php

namespace MorningMedley\View;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use MorningMedley\View\Classes\Cli;
use MorningMedley\View\Classes\Directives;
use MorningMedley\View\Console\ViewCacheCommand;
use MorningMedley\View\Console\ViewClearCommand;
use MorningMedley\View\Console\ViewMakeCommand;

class ViewServiceProvider extends \Illuminate\View\ViewServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->alias('view', \Illuminate\View\Factory::class);
        $this->app->alias('view', \Illuminate\Contracts\View\Factory::class);

        $this->mergeConfigFrom(__DIR__ . "/config/config.php", 'view');

        \Illuminate\Support\Facades\Blade::setFacadeApplication($this->app);
        \Illuminate\Support\Facades\View::setFacadeApplication($this->app);

        if (class_exists('\WP_CLI')) {
            $this->app->make(Cli::class);
        }

        $this->commands(
            ViewMakeCommand::class,
            ViewCacheCommand::class,
            ViewClearCommand::class,
        );
    }

    public function boot(): void
    {
        //        if(!is_dir())
        if ($namespaces = $this->app->make('config')->get('view.namespaces')) {
            foreach ((array) $namespaces as $namespace) {
                $this->app->make('view')->addNamespace(...$namespace);
            }
        }

        $this->app->make(Directives::class)->registerDirectives($this->app->make('blade.compiler'));
    }
}
