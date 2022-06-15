<?php

namespace App\Providers;

use App\Repositories\URLShortenerRepositoryEloquent;
use App\Repositories\URLShortenerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(URLShortenerRepositoryInterface::class, URLShortenerRepositoryEloquent::class);
    }
}
