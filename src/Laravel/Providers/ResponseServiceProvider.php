<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/11/18
	 * Time: 13.19
	 */

	namespace ServiceResponse\Laravel\Providers;

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
			class_alias(\ServiceResponse\Laravel\Facades\ResponseFacade::class, 'ServiceResponse');
		}

		/**
		 * Register Services
		 */
		protected function registerServices()
		{
			/**
			 * Service Response
			 */
			$this->app->singleton('service.response', 'ServiceResponse\Response\HttpResponse');
		}
	}