<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\Project;
use App\Observers\TaskObserver;
use App\Observers\ProjectObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultstringLength(191);
        Paginator::useBootstrap();
        Project::observe(ProjectObserver::class);
        Task::observe(TaskObserver::class);

    }
}
