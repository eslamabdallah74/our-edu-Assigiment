<?php

namespace App\Providers;

use App\Http\Controllers\Api\UserController;
use App\Interfaces\JsonDataInterface;
use App\Services\UserJsonData;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->app->when(UserController::class)
        //     ->needs(JsonDataInterface::class)
        //     ->give(function () {
        //         return new UserJsonData;
        //     });


        $this->app->bind(JsonDataInterface::class, UserJsonData::class);


    }
}
