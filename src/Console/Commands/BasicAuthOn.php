<?php

namespace ViktorMiller\LaravelBasicAuth\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

/**
 * 
 * @package  laravel-basic-auth
 * @author   Viktor Miller <phpfriq@gmail.com>
 */
class BasicAuthOn extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'basic-auth:on';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put the application into basic auth mode';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        file_put_contents(
            $this->laravel->storagePath().'/framework/basic-auth',
            json_encode($this->getDownFilePayload(), JSON_PRETTY_PRINT)
        );

        $this->comment('Application is now in basic auth mode.');
    }

    /**
     * Get the payload to be placed in the "basic" file.
     *
     * @return array
     */
    protected function getDownFilePayload()
    {
        $key = config('app.key');
        
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        
        return [
            'key' => hash_hmac('sha256', Str::random(40), $key),
            'time' => Carbon::now()->getTimestamp()
        ];
    }
}
