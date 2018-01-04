<?php

/**
 * 
 * @package  laravel-basic-auth
 * @author   Viktor Miller <phpfriq@gmail.com>
 */
return [
    
    /*
    |--------------------------------------------------------------------------
    | WWW-Authenticate (basic)
    |--------------------------------------------------------------------------
    | 
    | Wenn "BasicAuth Mode" eingeschaltet wird, prüft das System die
    | eingegebene Daten und vergleicht diese mit Daten, die unten aufgelistet
    | sind.
    */
    'identities' => collect([
        [
            env('BASIC_AUTH_USER', 'admin'), 
            env('BASIC_AUTH_PASSWORD', 'preview')
        ]
    ])
    
];