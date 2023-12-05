<?php

namespace MorningMedley\View;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\View\ViewServiceProvider;
use MorningMedley\View\Classes\Directives;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . "/config/config.php", 'view');
        $this->app->register(ViewServiceProvider::class);

        require_once __DIR__ . "/globals.php";
    }

    public function boot(): void
    {
        $this->app->make('config')
            ->set('view.paths', $this->resolveRelativePaths((array) $this->app->get('config')->get('view.paths')));
        $cacheDir = $this->app->make('config')->get('view.compiled');
        if (empty($cacheDir)) {
            $cacheDir = method_exists($this->app,
                'getCachedConfigPath') ? $this->app->joinPaths($this->app->getCachedConfigPath(), 'views') : $this->app->basePath('_cache/views');
        } else {
            $cacheDir = $this->app->basePath($cacheDir);
        }

        $this->app->get('config')
            ->set('view.compiled', $cacheDir);

        $this->app->make(Directives::class)->registerDirectives();
    }

    public function resolveRelativePaths(array $paths): array
    {
        return array_map(fn($path) => $this->app->basePath($path), $paths);
    }
}
