<?php

namespace Rschoonheim\LaravelPassportClientCredentialControl;

use Laravel\Passport\Passport;
use Rschoonheim\LaravelPassportClientCredentialControl\Commands\OauthClientCreateCommand;
use Rschoonheim\LaravelPassportClientCredentialControl\Observers\TokenObserver;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelPassportClientCredentialControlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-passport-client-credential-control')
            ->hasConfigFile()
            ->hasMigration('create_password_client_allowed_scopes_table')
            ->hasCommand(OauthClientCreateCommand::class);


        Passport::tokenModel()::observe(TokenObserver::class);
    }
}
