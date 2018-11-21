<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.04
	 */

	namespace ResponseHTTP\Response;

	use Illuminate\Database\Eloquent\Model;
	use ResponseHTTP\Response\Traits\CacheHeaders;
	use ResponseHTTP\Response\Traits\ConditionalHeaders;

	abstract class ResponseAbstract implements ResponseInterface
	{
		use CacheHeaders, ConditionalHeaders;

		protected $data;
		protected $links;
		protected $elements;
		protected $headers;
		protected $subArray;
		protected $forced;

		public function __construct() {
			$this->data = [];
			$this->links = [];
			$this->elements = [];
			$this->headers = [];
			$this->subArray = '';
			$this->forced = false;
		}

		protected function setInstance() {
			self::__construct();
		}

		protected function setHeaders(&$headers) {
			$headers += $this->headers;
		}

		/**
		 * Method for process response content
		 *
		 * @param $content
		 * @param string $type
		 * @return array
		 */
		protected function contentProcessor($content, string $type): array {
			$default = $this->contentData($type);

			if ($this->subArray)
				$default[$type] = array_add($default[$type], $this->subArray, $content);
			else
				array_set($default, $type, $content);

			return $default;
		}

		/**
		 * Method make array REST response
		 *
		 * @param string $type
		 * @return array
		 */
		protected function contentData(string $type): array {
			$default = [$type => []];

			if (!$this->forced) {
				if ($this->data)
					$default += ["data" => $this->data];
				if ($this->links)
					$default += ["links" => $this->links];
				if ($this->elements)
					$default += $this->elements;
			}
			return $default;
		}

		/**
		 * Method to add Data to error or success response
		 *
		 * @param array $data
		 * @return $this
		 */
		public function addData(array $data): ResponseService
		{
			$this->data = $data;
			return $this;
		}

		/**
		 * Adds to links array a default links array of model
		 *
		 * @param Model $model
		 * @param bool $hateoas
		 * @return ResponseService
		 */
		public function addModelLinks(Model $model, $hateoas = true) :ResponseService
		{
			if ($model instanceof Model && method_exists($model, 'getLinks')){
				$links = $model->getLinks();
				if ($links)
					$this->addLinks($links, $hateoas);
			}

			return $this;
		}

		/**
		 * Method to adds Links to error or success response
		 * $hateoas make $links to standard of Rest Api
		 *
		 * //Example links array
		 * links = [
		 *      ['self','localhost/user','GET'],
		 *      ['post','localhost/posts','GET']
		 * ]
		 *
		 * @param array $links
		 * @param bool $hateoas
		 * @return $this
		 */
		public function addLinks(array $links, bool $hateoas = true): ResponseService
		{
			foreach ($links as $link)
				$this->addLink($link, $hateoas);

			return $this;
		}

		/**
		 * Add singolar link to array links
		 *
		 * @param array $link
		 * @param bool $hateoas
		 * @return $this
		 */
		public function addLink(array $link, bool $hateoas = true): ResponseService
		{
			if ($hateoas) {
				$filtered = [
					"rel" => array_key_exists(0, $link) ? $link[0] : null,
					"href" => array_key_exists(1, $link) ? $link[1] : null,
					"method" => array_key_exists(2, $link) ? $link[2] : null,
				];
				array_push($this->links, $filtered);
			} else
				array_push($this->links, $link);
			return $this;
		}

		/**
		 * Method to add elements in response
		 *
		 * Example $item:
		 * ['output' => value] or ['output' => value, 'message' => value]
		 *
		 * @param array $item
		 * @return $this
		 */
		public function addElements(array $item): ResponseService {
			$this->elements += $item;
			return $this;
		}

		/**
		 * Method to add sub array in error or success response
		 *
		 * @param string $subArray
		 * @return $this
		 */
		public function withIncapsulated(string $subArray): ResponseService
		{
			$this->subArray = $subArray;
			return $this;
		}

		/**
		 * Add headers to headers response
		 *
		 * @param array $headers
		 * @return $this
		 */
		public function withHeaders(array $headers): ResponseService
		{
			$this->headers += $headers;
			return $this;
		}

		/**
		 * Forced skip add method
		 *
		 * @return $this
		 */
		public function forced():ResponseService {
			$this->forced = true;
			return $this;
		}
	}