<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/11/18
	 * Time: 13.42
	 */

	namespace ResponseHTTP\Response\Traits;

	use Illuminate\Support\Facades\URL;

	trait ModelREST
	{
		private static $method = [
			'GET',
			'POST',
			'PUT',
			'DELETE',
			'PATCH',
			'COPY',
			'HEAD',
			'OPTIONS',
			'LINK',
			'UNLINK',
			'PURGE',
			'LOCK',
			'UNLOCK',
			'PROPFIND',
			'VIEW',
		];

		/**
		 * array of links set in bootrREST as default for the specific model
		 *
		 * @var array
		 */
		protected $links = array();

		/**
		 * basic path of href link
		 * @var
		 */
		protected $basic_uri = '';

		/**
		 * method that you must implement in the model, using the methods to set the $basic_path and $links that will
		 * be added in the REST response After the method has been implemented, call it in the model constructor
		 * @return mixed
		 */
		abstract function bootREST();

		/**
		 * Get basic path of Model
		 *
		 * @return string
		 */
		public function getBasicUri(): string
		{
			return $this->basic_uri;
		}

		/**
		 * Set basic uri of links Model
		 *
		 * @param string $uri
		 *
		 * @return string
		 */
		public function setBasicUri(string $uri = null): void
		{
			if (null === $uri)
				$this->basic_uri = 'api/';
			else
				$this->basic_uri = strtolower($uri);
		}

		/**
		 * Set links of Model
		 * all parameters must be arrays
		 * (if last parameter is true/false links array will be overidden or not)
		 *
		 * @param array $links
		 *
		 * @return array
		 */
		public function setLinks(...$_): void
		{
			//check if links array will be override by new element
			if (is_bool(end($_))) {
				$override = end($_);
				if (true === $override)
					$this->links = array();
				array_splice($_, count($_)-1);
			}

			foreach ($_ as $key => $link)
				$this->addLink($_[$key]);
		}

		/**
		 * Add new element to links array
		 *
		 * @param $_link
		 */
		private function addLink($_link): void
		{
			if (!is_array($_link))
				$_link = array();
			else
				$_link = array_pad($_link,3,'');

			$link = array_combine(array('rel','href','method'),$_link);
			array_push($this->links,$link);
		}

		/**
		 * Get links of Model
		 * to recover only some links you have to pass as string parameters that contain the rel of the link that you want to take.
		 *
		 * @param string ...$_rel
		 *
		 * @return array
		 */
		public function getLinks(string ...$_rel): array
		{
			if (null == $_rel)
				return $this->links;

			$links = array();
			foreach ($this->links as $link) {
				foreach ($_rel as $str)
					in_array($str,$link) ? array_push($links, $link) :null;
			}
			return $links;
		}

		/**
		 * method for create href of link
		 *
		 * @param string $path
		 * @param bool   $override_basic_path
		 * @param bool   $external
		 *
		 * @return string
		 */
		private function href(string $path = '', bool $override_basic_path = false, bool $external = false): string
		{
			if (!$path)
				return URL::current();

			if ($override_basic_path)
				return $external ? $path : url($path);

			return url($this->basic_path . $path);
		}

		/**
		 * method for create name of request link method
		 *
		 * @param string $method
		 *
		 * @return string
		 */
		private function method(string $method): string
		{
			$value = strtoupper($method);
			$found_method = in_array($value, self::$method) ? $value : '';
			return $found_method;
		}

		/**
		 * method for create rel of link
		 *
		 * @param string $name
		 *
		 * @return string
		 */
		private function rel(string $name): string
		{
			return strtolower($name);
		}
	}