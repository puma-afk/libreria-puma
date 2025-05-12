<?php

namespace puma\libreria;

use Illuminate\Support\ServiceProvider;
//use puma\libreria\Console\Commands\ValidarTextoLibreria;

class ValidacionLibreriaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \puma\libreria\Console\Commands\ValidarTextoLibreria::class,
            ]);
        }
    }

    public function register()
    {
        $this->app->singleton(ArchivoService::class, function ($app) {
            return new ArchivoService();
        });
    }
}