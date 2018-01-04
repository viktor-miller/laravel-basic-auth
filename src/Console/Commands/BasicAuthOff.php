<?php

namespace ViktorMiller\LaravelBasicAuth\Console\Commands;

use Illuminate\Console\Command;

/**
 * 
 * @package  laravel-basic-auth
 * @author   Viktor Miller <phpfriq@gmail.com>
 */
class BasicAuthOff extends Command
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
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        @unlink($this->laravel->storagePath() .'/framework/basic-auth');

        $this->info('Application is now live.');
    }
}
