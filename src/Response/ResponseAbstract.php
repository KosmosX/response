<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.04
	 */

	namespace ResponseHTTP\Response;

	use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;

	class ResponseAbstract extends BaseJsonResponse
	{
		protected static $original = [
			'data' => [],
			'links' => [],
		];
		protected $options;

		public function __construct($data = NULL, int $status = 200, array $headers = array(), bool $json = false)
		{
			$this->options = $json;
			parent::__construct($data, $status, $headers, $json);
		}

		protected function dispatcher(string $type, ...$construct) {
			list($content,$status,$headers,$json) = $construct;
			$content = $this->contentProcessor($content,$type);
			self::__construct($content,$status,$headers,$json);
		}

		/**
		 * Method for process response content
		 *
		 * @param $content
		 * @param string $type
		 * @return array
		 */
		protected function contentProcessor($content, string $type): array {
			$this->content = [$type => $content];

			foreach (self::$original as $key => $item)
				if(count($item)){
					$el = array_pop($item);
					if (array_key_exists($key,$this->content))
						$this->content[$key] += [key($el) => array_values($el)];
					else
						$this->content += [$key => array_pop($item)];
				}

			return $this->getContent();
		}

		/**
		 * Set a header on the Response.
		 *
		 * @param  string  $key
		 * @param  array|string  $values
		 * @param  bool    $replace
		 * @return $this
		 */
		public function withHeader($key, $values, $replace = true)
		{
			$this->headers->set($key, $values, $replace);

			return $this;
		}

		/**
		 * Add an array of headers to the response.
		 *
		 * @param  \Symfony\Component\HttpFoundation\HeaderBag|array  $headers
		 * @return $this
		 */
		public function withHeaders($headers)
		{
			if ($headers instanceof HeaderBag) {
				$headers = $headers->all();
			}

			foreach ($headers as $key => $value) {
				$this->headers->set($key, $value);
			}

			return $this;
		}

		/**
		 * Method to add Data to error or success response
		 *
		 * @param array $data
		 * @return $this
		 */
		public function addData(array $data): ResponseService
		{
			array_push(self::$original['data'], $data);
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
				array_push(self::$original['links'], [$filtered]);
			} else
				array_push(self::$original['links'], [$link]);
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
		public function addElements(array $elements, bool $override = true): ResponseService {
			foreach ($elements as $key => $element)
				if (true === $override || !array_key_exists($key,self::$original))
					self::$original += [$key => $element];

			return $this;
		}
	}