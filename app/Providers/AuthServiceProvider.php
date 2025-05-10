<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\User;
use App\Policies\CoursePolicy;
use App\Policies\QuizPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Course::class => CoursePolicy::class,
        Quiz::class => QuizPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for activity logs
        Gate::define('viewActivityLogs', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('clearActivityLogs', function (User $user) {
            return $user->hasRole('admin');
        });
    }
}
