<?php

namespace JoisarJignesh\Bigbluebutton;

use BigBlueButton\BigBlueButton;
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
                __DIR__.'/../config/bigbluebutton.php' => config_path('bigbluebutton.php'),
            ], 'bigbluebutton-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'bigbluebutton');

        $server_base_url = $this->app['config']->get('bigbluebutton.BBB_SERVER_BASE_URL');
        $server_salt = $this->app['config']->get('bigbluebutton.BBB_SECURITY_SALT');

        putenv("BBB_SERVER_BASE_URL=$server_base_url");
        putenv("BBB_SECURITY_SALT=$server_salt");

        $this->app->bind('Bigbluebutton', function () {
            return new Bbb(new BigBlueButton());
        });
    }
}
