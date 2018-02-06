## Laravel basic authentication ##

This package for Laravel 5.4/5.5 project.

The package put the application into basic authentication mode

### Features ###
- artisan commands to turn on/off
- configuration file
- support logout force
- multilanguage support
- temporary credentials support
- expire date support

### Installation ###

Add package to your **composer.json** file:

    composer require viktor-miller/laravel-basic-auth
	
For Laravel <= 5.4 add service provider to **config/app.php**

	'providers' => [
   		...
      	ViktorMiller\LaravelBasicAuth\ServiceProvider::class,
      	...
	]

### Console ###

To put the applicaion into basic auth mode

	php artisan basic-auth:on

To bring the application out of basic auth mode

	php artisan basic-auth:off

### Publish ###

By default get package login informarmation from .env file. If you want to add other info publish config file.

	php artisan vendor:publish --tag=basic-auth:config
