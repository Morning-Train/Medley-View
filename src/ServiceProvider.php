<?php

namespace MorningMedley\View;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\View\ViewServiceProvider;

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
        $this->app->get('config')
            ->set('view.paths', $this->resolveRelativePaths((array)$this->app->get('config')->get('view.paths')));
        $this->app->get('config')
            ->set('view.compiled',
                $this->resolveRelativePaths((array) $this->app->get('config')->get('view.compiled'))[0]);
    }

    public function resolveRelativePaths(array $paths): array
    {
        return array_map(fn($path) => $this->app->basePath($path), $paths);
    }
}
