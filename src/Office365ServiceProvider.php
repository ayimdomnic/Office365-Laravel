<?php

namespace Ayimdomnic\Office365Laravel;

use Illuminate\Support\ServiceProvider;
/**
* the service provider class
* This package is for AyimDomnic's personal use
* I can change and tweak it however way I wish
*/
class Office365ServiceProvider extends ServiceProvider
{
	
	protected $defer = false;

	public function boot()
	{
		// use this if your package has views
        //$this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'skeleton');
        
        // use this if your package has lang files
        //$this->loadTranslationsFrom(__DIR__.'/resources/lang', 'skeleton');
        
        // use this if your package has routes
        //$this->setupRoutes($this->app->router);
		// *Todo::make a config that's published on register
		$this->publishes([
			__DIR__.'/config/config.php' => config_path('office365.php'),
		]);
	}

	public function register()
	{
		$this->registerSkeleton();
	}

	public function registerSkeleton()
	{
		$this->app->bind('Office365', function($app){

			return new Office365($app);
		});
	}
}