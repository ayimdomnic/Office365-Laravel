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
		// *Todo::make a config that's published on register
		$this->publishes([]);
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