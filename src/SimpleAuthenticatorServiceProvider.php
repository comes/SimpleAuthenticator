<?php

namespace Comes\SimpleAuthenticator;

use Comes\SimpleAuthenticator\Commands\SimpleAuthenticatorCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SimpleAuthenticatorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('simpleauthenticator')
            ->hasConfigFile()
            ->hasCommand(SimpleAuthenticatorCommand::class);
    }
}
