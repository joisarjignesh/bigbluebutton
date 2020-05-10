<?php

namespace JoisarJignesh\Bigbluebutton;

use Illuminate\Support\ServiceProvider;

class BigbluebuttonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/bigbluebutton.php' => config_path('bigbluebutton.php'),
            ], 'bigbluebutton-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bigbluebutton.php', 'bigbluebutton');

        /*        putenv("BBB_SERVER_BASE_URL=$this->app['config']->get('bigbluebutton.BBB_SERVER_BASE_URL')");
                putenv("BBB_SECURITY_SALT=$this->app['config']->get('bigbluebutton.BBB_SECURITY_SALT')");*/

        $this->app->singleton('Bigbluebutton', function () {
            return new Bbb(
                new Bigbluebutton(
                    $this->app['config']->get('bigbluebutton.BBB_SERVER_BASE_URL'),
                    $this->app['config']->get('bigbluebutton.BBB_SECURITY_SALT')
                )
            );
        });
    }
}
