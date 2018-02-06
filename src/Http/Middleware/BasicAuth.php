<?php

namespace ViktorMiller\LaravelBasicAuth\Http\Middleware;

use Closure;
use ViktorMiller\LaravelBasicAuth\Services\BasicAuth as BasicAuthService;

/**
 * 
 * @package  laravel-basic-auth
 * @author   Viktor Miller <phpfriq@gmail.com>
 */
class BasicAuth
{
    /**
     * @var BasicAuthService
     */
    protected $service;
    
    /**
     * Creare new middleware instance
     * 
     * @param BasicAuthService $service
     * @retun void
     */
    public function __construct(BasicAuthService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {   
        $credentials = [$request->getUser(), $request->getPassword()];
        
        if (! $this->service->passes($credentials)) {
            return response('Unauthorized', 401, [
                'WWW-Authenticate' => 'Basic'
            ]);
        }

        return $next($request);
    }
}
