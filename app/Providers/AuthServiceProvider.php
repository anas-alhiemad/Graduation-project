<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Policies\FilePolicy;
use App\Policies\QuizPolicy;
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
          \App\Models\Quiz::class => \App\Policies\QuizPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('upload', [FilePolicy::class, 'upload']);
        Gate::define('view', [FilePolicy::class, 'view']);
        Gate::define('create', [QuizPolicy::class, 'create']);
        Gate::define('view', [QuizPolicy::class, 'view']);
        Gate::define('delete', [QuizPolicy::class, 'delete']);
        Gate::define('update', [QuizPolicy::class, 'update']);

    }
}
