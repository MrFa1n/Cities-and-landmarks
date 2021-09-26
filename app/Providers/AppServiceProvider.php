<?php

namespace App\Providers;

// lib URL for SSL
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        // //check that app is local
        // if ($this->app->isLocal()) {
        // //if local register your services you require for development
        //     $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        // } else {
        // //else register your services you require for production
        //     $this->app['request']->server->set('HTTPS', true);
        // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    
    public function boot(UrlGenerator $url)
    {
        // Connect function from lib URL
        URL::forceScheme('https');
        
    }
}
