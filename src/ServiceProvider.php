<?php

namespace ViktorMiller\LaravelBasicAuth;

use Illuminate\Routing\Router;
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
     * Package root path
     * 
     * @var string 
     */
    protected $packageRoot;
    
    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);
        
        $this->root = __DIR__ .'/../';
    }
    
    /**
     * Bootstrap any application services.
     *
     * @param  Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $this->defineConfigPublish();
        $this->initTranslationPublish();
        $this->initConsoleCommands();
        
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
                Commands\On::class,
                Commands\Off::class,
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
    
    /**
     * Init translation publish
     * 
     * @return void
     */
    protected function initTranslationPublish()
    {
        $path = $this->root .'resources/lang';

        $this->loadTranslationsFrom($path, 'basic-auth');
        $this->publishes([
            $path => resource_path('lang/vendor/basic-auth'),
        ], 'basic-auth:translations');
    }
}
