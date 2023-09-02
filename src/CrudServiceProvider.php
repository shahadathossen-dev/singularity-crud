<?php

namespace Singularity\Crud;

use Singularity\Crud\Commands\CrudGenerator;
use Illuminate\Support\ServiceProvider;
use Singularity\Crud\Commands\ControllerGenerator;
use Singularity\Crud\Commands\MigrationGenerator;
use Singularity\Crud\Commands\RequestGenerator;
use Singularity\Crud\Commands\ViewGenerator;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load package commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CrudGenerator::class,
                ControllerGenerator::class,
                MigrationGenerator::class,
                RequestGenerator::class,
                ViewGenerator::class,
            ]);
        }

        // Load view layouts
        $this->loadViewsFrom(__DIR__.'/../views', 'singularity-crud');
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load package public assets
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/singularity-crud', 'singularity-crud'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../assets' => public_path('singularity-crud', 'singularity-crud'),
        ], 'laravel-assets');
    }
}
