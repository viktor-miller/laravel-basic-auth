<?php

namespace ViktorMiller\LaravelBasicAuth\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use ViktorMiller\LaravelBasicAuth\Services\BasicAuth;

/**
 * 
 * @package  laravel-basic-auth
 * @author   Viktor Miller <phpfriq@gmail.com>
 */
class On extends Command
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
        $this->service->create([
            'identities' => $this->identities(),
            'expired' => $this->expire()
        ]);

        $this->comment(__('basic-auth::messages.on'));
    }
    
    /**
     * Get identities
     * 
     * @return array
     */
    protected function identities()
    {
        $identities = []; 
        
        if ($this->confirm(__('basic-auth::messages.config-usage'), true)) {
            $identities = config('basic-auth.identities', []);
            
            if (! count($identities)) {
                $this->error(__('basic-auth::messages.config-empty'));
            }
        }
        
        if (count($identities)) {
            if ($this->confirm(__('basic-auth::messages.temp-usage'))) {
                array_push($identities, $this->askIdentities());
            }
        } else {    
            array_push($identities, $this->askIdentities());
        }
        
        foreach ($identities as $key => $identity) {
            $identities[$key] = [
                array_get($identity, 0), 
                Hash::make(array_get($identity, 1))
            ];
        }
        
        return $identities;
    }
    
    /**
     * Get temporary identities
     * 
     * @return array
     */
    protected function askIdentities()
    {
        $this->line(__('basic-auth::messages.temp-enter'));
        
        return [
            $this->ask(__('basic-auth::messages.temp-user-enter')), 
            $this->secret(__('basic-auth::messages.temp-pass-enter'))
        ];
    }
    
    /**
     * Get expire Date (timestamp)
     * 
     * @return string
     */
    protected function expire()
    {
        if ($this->confirm(__('basic-auth::messages.time-limit'))) {
            return Carbon::now()
                    ->addDays($this->ask(__('basic-auth::messages.number.days'), 0))
                    ->addHours($this->ask(__('basic-auth::messages.number.hours'), 0))
                    ->addMinutes($this->ask(__('basic-auth::messages.number.minutes'), 0))
                    ->timestamp;
        }
        
        return null;
    }
}
