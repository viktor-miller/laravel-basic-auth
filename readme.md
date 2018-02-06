## Laravel HTTP Basic Authentication (BA) ##

This package activates the HTTP Basic Authentication mode in the application on Laravel> = 5.4

After you call the command to activate the HTTP Basic Authentication mode, you will be asked to use the default authentication data from the configuration file. These data are permanent. Then you will be asked to use the temporary login data, which will be active only for the current mode. If you enter temporary data, it will be added to the data stack to the existing default login data from the config file (if you selected it). This allows you to have several keys to log in (for example permanent key for the admin and temporary for the customer).
If you do not use the authentication data from the configuration file, you will be forced to enter the temporary login data every time.
At the end you can enter an optional time interval in days and/or hours and/or minutes, after which the HTTP Basic Authentication mode will be automatically turned off.

### Features ###

- Artisan commands to turn on/off
- Configuration file for storing authentication data
- Support of forced logout. After reactivation, all logged on users will be forced to enter authentication data again.
- Multilanguage support (EN, DE, RU)
- Support to set temporary authentication data
- Support set a time limit (in Days, Hours or Minutes). At the end of this time, the HTTP Basic Authentication mode will be automatically turned off.

### Installation ###

Add package to your **composer.json** file:

    composer require viktor-miller/laravel-basic-auth

For Laravel <= 5.4 add service provider to **config/app.php**

    'providers' => [
   	    ...
      	ViktorMiller\LaravelBasicAuth\ServiceProvider::class,
      	...
    ]

Call artisan command to publish config file

    php artisan vendor:publish --tag=basic-auth:config

Change the configuration file **basic-auth.php** as you like

    <?php

	return [
        'identities' => [
    		[
                env('BASIC_AUTH_USER', 'admin'),
                env('BASIC_AUTH_PASSWORD', 'preview')
    		],
    		...
    		[
                env('BASIC_AUTH_USER', 'admin2'),
                env('BASIC_AUTH_PASSWORD', 'secret')
    		],
    		...
        ]
	];

Note: in the configuration files, use the passwords as they are, when you turn on the HTTP Basic Authenticationmode, the passwords will be written to the temporary file in **storage/framework/basicauth**. The passwords will be hashed.

### Usage ###

To put the application into basic HTTP authentication mode, call this command and follow the instructions

    php artisan basic-auth:on

To bring the application out of HTTP Basic Authentication mode call

    php artisan basic-auth:off

### Translation ###

If you want to add change translation files call

    php artisan vendor:publish --tag=basic-auth:translations
