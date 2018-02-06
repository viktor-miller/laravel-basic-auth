<?php

namespace ViktorMiller\LaravelBasicAuth\Services;

use StdClass;
use Carbon\Carbon;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * Description of BasicAuth
 *
 * @author viktormiller
 */
class BasicAuth
{
    /**
     * Session key name
     */
    const SESSION_KEY = '_basic-auth';
    
    /**
     * Filename
     */
    const FILE_NAME = 'basicauth';
    
    /**
     * @var string
     */
    protected $file;
    
    /**
     * @var StdClass 
     */
    protected $data;
    
    /**
     * Check if base auth should be active
     * 
     * @return boolean
     */
    protected function active()
    {   
        $properties = ['key', 'identities', 'expired'];
        
        if (! file_exists($this->file())) {
            return false;
        } elseif (! $this->data() instanceof StdClass) {
            return false;
        }
        
        foreach ($properties as $property) {
            if (! property_exists($this->data(), $property)) {
                return false;
            }
        }
        
        if ($this->expired($this->data()->expired)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Authenticate users
     * 
     * @return boolean
     */
    public function passes(array $credentials)
    {
        if ($this->active()) {
            try {
                $this->checkSession();
                $this->checkCredentials($credentials);
            } catch (RuntimeException $ex) {
                $this->removeSession();
                return false;
            }
            
            $this->storeSession();
        } else {
            $this->flush();
        }
        
        return true;
    }
    
    /**
     * Create new file
     * 
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $key = config('app.key');
        
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        
        file_put_contents($this->file(), json_encode(array_merge($data, [
            'key' => hash_hmac('sha256', Str::random(40), $key)
        ]), JSON_PRETTY_PRINT));
    }
    
    /**
     * Remove data from the session and unlink the file
     * 
     * @return void
     */
    public function flush()
    {
        $this->removeFile();
    }
    
    /**
     * Check if base auth is expired
     * 
     * @param  string $timestamp
     * @return bool
     */
    protected function expired($timestamp)
    {
        return $timestamp
            ? Carbon::now()->gte(Carbon::createFromTimestamp($timestamp))
            : false;
    }
    
    /**
     * Check whether there is a key in the session 
     * and whether it matches what is in the file
     * 
     * @throws RuntimeException
     */
    protected function checkSession()
    {
        if ($this->sessionKey() && $this->sessionKey() != $this->data()->key) {
            throw new RuntimeException('Invalid session key');
        }
    }
    
    /**
     * Сheck the credentials for logging on to the system
     * 
     * @param  array $credentials
     * @throws RuntimeException
     */
    protected function checkCredentials(array $credentials)
    {   
        foreach ($this->data()->identities as $identity) {
            if (array_get($identity, 0) != array_get($credentials, 0)) {
                continue;
            }
            
            if (Hash::check(array_get($credentials, 1), array_get($identity, 1))) {
                return;
            }
        }
        
        throw new RuntimeException('Invalid credentials');
    }
    
    /**
     * Remove key from the session
     * 
     * @return void
     */
    protected function removeSession()
    {
        if ($this->sessionKey()) {
            $this->session()->remove(self::SESSION_KEY);
        }
    }
    
    /**
     * Remove file from drive
     * 
     * @return void 
     */
    protected function removeFile()
    {
        if (file_exists($this->file())) {
            unlink($this->file());
        }
    }
    
    /**
     * Store key in the session
     * 
     * @return void
     */
    protected function storeSession()
    {
        if (! $this->sessionKey()) {
            $this->session()->put(self::SESSION_KEY, $this->data()->key);
        }
    }
    
    /**
     * Get temporary data from file
     * 
     * @return \StdClass
     */
    protected function data()
    {
        if (is_null($this->data) && 
            $content = file_get_contents($this->file())) {
            $json = json_decode($content);
                
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    $this->data = $json;
                    break;
            }
        }
        
        return $this->data;
    }
    
    /**
     * Get session driver
     * 
     * @return \Illuminate\Contracts\Session\Session
     */
    protected function session()
    {
        return Session::driver();
    }
    
    /**
     * Get key from the session
     * 
     * @return string|null
     */
    protected function sessionKey()
    {
        return $this->session()->get(self::SESSION_KEY);
    }
    
    /**
     * Get temporary file path
     * 
     * @return string
     */
    protected function file()
    {
        if (is_null($this->file)) {
            $this->file = storage_path('framework/'. self::FILE_NAME);
        }
        
        return $this->file;
    }
}
