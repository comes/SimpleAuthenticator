<?php

namespace Comes\SimpleAuthenticator;

use Comes\SimpleAuthenticator\Commands\SimpleAuthenticatorCommand;
use Illuminate\Support\ServiceProvider;

class SimpleAuthenticatorServiceProvider extends ServiceProvider
{
    /** @inheritDoc */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/simpleauthenticator.php', 'simpleauthenticator'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/simpleauthenticator.php' => config_path('simpleauthenticator.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SimpleAuthenticatorCommand::class,
            ]);
        }
    }
}
