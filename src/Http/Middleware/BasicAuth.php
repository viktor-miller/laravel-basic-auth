<?php

namespace ViktorMiller\LaravelBasicAuth\Http\Middleware;

use Closure;

/**
 * 
 * @package  laravel-basic-auth
 * @author   Viktor Miller <phpfriq@gmail.com>
 */
class BasicAuth
{
    /**
     * @var string
     */
    protected $file;
    
    /**
     * Creare new middleware instance
     * 
     * @retun void
     */
    public function __construct()
    {
        $this->file = storage_path() .'/framework/basic-auth';
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
        if (! $this->isOn()) {
            return $next($request);
        }
        
        $json = $this->json();
        $session = $request->session()->get('_basic-auth');
        
        if ($session && $session !== $json->key) {
            $request->session()->remove('_basic-auth');
            
            return $this->response();
        }
        
        $data = [$request->getUser(), $request->getPassword()];
        
        if (! config('basic-auth.identities')->contains($data)) {
            return $this->response();
        }
        
        if (! $session) {
            $request->session()->put('_basic-auth', $json->key);
        }

        return $next($request);
    }
    
    /**
     * send response
     * 
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function response()
    {
        return response('Unauthorized', 401, [
            'WWW-Authenticate' => 'Basic'
        ]);
    }
    
    /**
     * 
     * @return bool
     */
    protected function isOn()
    {
        return file_exists($this->file);
    }
    
    /**
     * 
     * @return \StdClass
     */
    protected function json()
    {
        return json_decode(file_get_contents($this->file));
    }
}
