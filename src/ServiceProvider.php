<?php

namespace ViktorMiller\LaravelBasicAuth;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use ViktorMiller\LaravelBasicAuth\Console\Commands;
use ViktorMiller\LaravelBasicAuth\Http\Middleware\BasicAuth;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * 
 * @package  laravel-basic-auth
 * @author   Viktor Miller <phpfriq@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param  Kernel $kernel
     * @return void
     */
    public function boot(Router $router)
    {
        $this->initConsoleCommands();
        $this->defineConfigPublish();
        
        $router->pushMiddlewareToGroup('web', BasicAuth::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }
    
    /**
     * Init console commands
     * 
     * @return void
     */
    protected function initConsoleCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\BasicAuthOn::class,
                Commands\BasicAuthOff::class,
            ]);
        }
    }
    
    /**
     * Define config publish
     * 
     * @return void
     */
    protected function defineConfigPublish()
    {
        $this->publishes([
            __DIR__ .'/../config/basic-auth.php' => config_path('basic-auth.php'),
        ], 'basic-auth:config');
    }
    
    /**
     * Merge default configs
     * 
     * @return void
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ .'/../config/basic-auth.php', 'basic-auth'
        );
    }
}
