<?php

namespace CodingWisely\Commitvel;

use CodingWisely\Commitvel\Commands\CommitvelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CommitvelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('commitvel')
            ->hasCommand(CommitvelCommand::class);
    }
}
