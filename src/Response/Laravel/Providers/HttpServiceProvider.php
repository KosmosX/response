<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/11/18
	 * Time: 13.19
	 */

	namespace ResponseHTTP\Response\Laravel\Providers;

	use Illuminate\Support\ServiceProvider;

	class HttpServiceProvider extends ServiceProvider
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
			class_alias(\ResponseHTTP\Response\Laravel\Facades\ResponseFacade::class, 'HttpResponse');
		}

		/**
		 * Register Services
		 */
		protected function registerServices()
		{
			/**
			 * Service Response
			 */
			$this->app->singleton('service.response', 'ResponseHTTP\Response\HttpResponse');
		}
	}