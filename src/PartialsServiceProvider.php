<?php

namespace AwStudio\Partials;

use AwStudio\Partials\Console\MakePartialsCommand;
use Illuminate\Support\ServiceProvider;

class PartialsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakePartialsCommand::class,
            ]);
        }
    }

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MakePartialsCommand::class, function ($app) {
            return new MakePartialsCommand($app['files']);
        });
    }
}
