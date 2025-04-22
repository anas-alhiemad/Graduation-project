<?php

namespace App\Providers;

use App\Interfaces\RepositoryInterface;
use App\Repositories\StudentRepository;
use App\Repositories\TrainerRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\SecretaryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, StudentRepository::class);
        $this->app->bind(RepositoryInterface::class, SecretaryRepository::class);
        $this->app->bind(RepositoryInterface::class, TrainerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
