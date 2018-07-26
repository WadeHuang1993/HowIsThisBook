<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Crawler\KingStoneCrawler;
use Goutte\Client;

class CrawlerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Crawler', function () {
            return new KingStoneCrawler(new Client);
        });
    }
}
