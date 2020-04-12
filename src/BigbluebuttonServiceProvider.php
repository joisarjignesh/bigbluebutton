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
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bigbluebutton');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'bigbluebutton');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('bigbluebutton.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/bigbluebutton'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/bigbluebutton'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/bigbluebutton'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'bigbluebutton');

        $server_base_url = $this->app['config']->get('bigbluebutton.BBB_SERVER_BASE_URL');
        $server_salt = $this->app['config']->get('bigbluebutton.BBB_SECURITY_SALT');

        putenv("BBB_SERVER_BASE_URL=$server_base_url");
        putenv("BBB_SECURITY_SALT=$server_salt");
        // Register the main class to use with the facade
        $this->app->bind('Bigbluebutton', function () {
            return new Bbb(new BigBlueButton());
        });
    }
}
