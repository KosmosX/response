<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/11/18
	 * Time: 13.42
	 */

	namespace ResponseHTTP\Response\Traits;

	use Illuminate\Support\Facades\URL;

	Trait ModelREST
	{
		/**
		 * array of links set in bootrREST as default for the specific model
		 *
		 * @var array
		 */
		private static $links = [];

		/**
		 * basic path of href link
		 * @var
		 */
		private static $basic_path = '';

		/**
		 * method that you must implement in the model, using the methods to set the $basic_path and $links that will be added in the REST response
		 * After the method has been implemented, call it in the model constructor
		 * @return mixed
		 */
		abstract function bootREST();

		/**
		 * Set basic path of Model
		 *
		 * @param string $basic_path
		 * @return string
		 */
		public function setBasicPath(string $basic_path = ''): string
		{
			self::$basic_path = $basic_path ?: 'api/' . env('API_STABLE_VERSION') . '/';
			return $this->getBasicPath();
		}

		/**
		 * Get basic path of Model
		 *
		 * @return string
		 */
		public function getBasicPath(): string
		{
			return self::$basic_path;
		}

		/**
		 * Set links of Model
		 *
		 * @param array $links
		 * @return array
		 */
		public function setLinks(array $links): array
		{
			self::$links = $links;
			return $this->getLinks();
		}

		/**
		 * Get links of Model
		 * @return array
		 */
		public function getLinks(): array
		{
			return self::$links;
		}

		/**
		 * method for create href of link
		 *
		 * @param string $path
		 * @param bool $override_basic_path
		 * @param bool $external
		 * @return string
		 */
		private function href(string $path = '', bool $override_basic_path = false, bool $external = false): string
		{
			if (!$path)
				return URL::current();

			if ($override_basic_path)
				return $external ? $path : url($path);

			return url(self::$basic_path . $path);
		}

		/**
		 * method for create name of request link method
		 *
		 * @param string $method
		 * @return string
		 */
		private function method(string $method): string
		{
			return $method;
		}

		/**
		 * method for create rel of link
		 * @param string $name
		 * @return string
		 */
		private function rel(string $name): string
		{
			return $name;
		}
	}