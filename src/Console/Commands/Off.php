<?php

namespace ViktorMiller\LaravelBasicAuth\Console\Commands;

use Illuminate\Console\Command;
use ViktorMiller\LaravelBasicAuth\Services\BasicAuth;

/**
 * 
 * @package  laravel-basic-auth
 * @author   Viktor Miller <phpfriq@gmail.com>
 */
class Off extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'basic-auth:off';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring the application out of basic auth mode';
    
    /**
     * @var BasicAuth
     */
    protected $service;
    
    /**
     * 
     * @param BasicAuth $service
     */
    public function __construct(BasicAuth $service)
    {
        parent::__construct();
        
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->service->flush();
        
        $this->info(__('basic-auth::messages.off'));
    }
}
