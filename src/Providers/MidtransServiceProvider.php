<?php

namespace BlissJaspis\Midtrans\Providers;

use BlissJaspis\Midtrans\Midtrans;
use Illuminate\Support\ServiceProvider;

class MidtransServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/midtrans.php',
            'midtrans'
        );

        $this->app->singleton(Midtrans::class);
    }
    
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/midtrans.php' => config_path('midtrans.php'),
            ], 'config');
        }
    }
}