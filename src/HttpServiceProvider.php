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
		}

		/**
		 * Register Services
		 */
		protected function registerServices()
		{
			/**
			 * Service Response
			 */
			$this->app->singleton('service.response', 'Core\Services\Response\ResponseService');
			$this->app->singleton('service.response.rest', 'Core\Services\Response\ResponseREST');
		}

		/**
		 * Load alias
		 */
		protected function registerAlias()
		{
			$aliases=[
				'ResponseService' => \ResponseHTTP\Response\ResponseFacade::class,
				'Response' => \ResponseHTTP\Response\ResponseFacade::class,
				'ResponseREST' => \ResponseHTTP\Response\ResponseRESTFacade::class,
			];

			foreach ($aliases as $key => $value){
				class_alias($value, $key);
			}
		}
	}