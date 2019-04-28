<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/11/18
	 * Time: 13.19
	 */

	namespace Kosmosx\Laravel\Providers;

	use Illuminate\Support\ServiceProvider;

	class ResponseServiceProvider extends ServiceProvider
	{
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->registerAlias();
			$this->registerServices();
		}

		/**
		 * Load alias
		 */
		protected function registerAlias()
		{
			class_alias(\Kosmosx\Laravel\Facades\FactoryResponse::class, 'FactoryResponse');
		}

		/**
		 * Register Services
		 */
		protected function registerServices()
		{
			/**
			 * Service Response
			 */
			$this->app->bind('factory.response', 'Kosmosx\Response\Factory\FactoryResponse');
			$this->app->singleton('service.response', 'Kosmosx\Response\RestResponse');
		}
	}