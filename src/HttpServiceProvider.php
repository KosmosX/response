<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/11/18
	 * Time: 13.19
	 */

	namespace ResponseHTTP;

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
			$this->registerMiddleware();
		}

		/**
		 * Load alias
		 */
		protected function registerAlias()
		{
			class_alias(\ResponseHTTP\Response\Facades\ResponseFacade::class, 'Response');
		}

		/**
		 * Register Services
		 */
		protected function registerServices()
		{
			/**
			 * Service Response
			 */
			$this->app->singleton('service.response', 'ResponseHTTP\Response\ResponseService');
		}

		/**
		 * Register middleware
		 */
		protected function registerMiddleware()
		{
			$this->app->middleware([
				\ResponseHTTP\Response\Middleware\CorsMiddleware::class
			]);
		}
	}