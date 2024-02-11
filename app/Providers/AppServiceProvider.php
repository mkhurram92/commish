<?php

namespace App\Providers;


use App\Console\Commands\upfrontvariance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Console\Commands\Deals;
use App\Console\Commands\variance;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Deals::class,
            variance::class,
            upfrontvariance::class
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $is_admin = 0;
                $all_modules = [];
                if (Auth::user()->role == 'admin') {
                    $is_admin = 1;
                } else {
                    $all_modulestemp = Auth::user()->modules()->pluck('module_name');
                    if (!empty($all_modulestemp)) {
                        $all_modules = $all_modulestemp->toArray();
                    }
                }

                //...with this variable
                $view->with('user_is_admin', $is_admin);
                $view->with('module_permissions', $all_modules);
            }

        });

    }
}
