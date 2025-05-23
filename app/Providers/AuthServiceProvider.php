<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Policies\FilePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         \App\Models\Section_File::class => \App\Policies\FilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('upload', [FilePolicy::class, 'upload']);

    }
}
