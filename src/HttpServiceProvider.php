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
			$aliases = [
				'Response' => \ResponseHTTP\Response\Facades\ResponseFacade::class,
				'ResponseREST' => \ResponseHTTP\Response\Facades\ResponseRESTFacade::class,
			];

			foreach ($aliases as $key => $value) {
				class_alias($value, $key);
			}
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
			$this->app->singleton('service.response.rest', 'ResponseHTTP\Response\ResponseREST');
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