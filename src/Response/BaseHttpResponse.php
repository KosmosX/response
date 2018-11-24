<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.04
	 */

	namespace ResponseHTTP\Response;

	use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;

	class BaseHttpResponse extends BaseJsonResponse
	{
		protected static $original = [];

		protected function dispatcher(string $type, ...$construct) {
			list($content,$status,$headers, $json) = $construct;
			unset($construct);

			self::__construct('',$status,$headers,$json);

			if (null === $content)
				$content = array_key_exists((string)$status,self::$statusTexts) ? self::$statusTexts[$status] : new \ArrayObject();
			$this->withContent($type,$content,false,$json);
		}

		/**
		 * Set a header on the Response.
		 *
		 * @param  string  $key
		 * @param  array|string  $values
		 * @param  bool    $replace
		 * @return $this
		 */
		public function withHeader($key, $values, $replace = true) :BaseHttpResponse
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
		public function withHeaders($headers) :BaseHttpResponse
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
		 * @param string $type
		 * @param array  $content
		 *
		 * @return $this
		 */
		public function withContent(string $type = 'content', $content = array(), bool $override = false, bool $json = false) :BaseHttpResponse{

			if (array_key_exists($type,self::$original))
				self::$original[$type] = $override ?  $content : array(self::$original[$type], $content);
			else
				self::$original += [$type => $content];

			$json ? $this->setJson(self::$original) : $this->setData(self::$original);

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
		public function withContents(array $contents, bool $override = false): BaseHttpResponse {
			foreach ($contents as $type => $content)
				$this->withContent($type, $content, $override);
			return $this;
		}

		/**
		 * Alias to add Data to content
		 *
		 * @param $data
		 * @return $this
		 */
		public function withData($data): BaseHttpResponse
		{
			$this->withContent('data',$data);
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
		public function withLinks(array $links, bool $hateoas = true): BaseHttpResponse
		{
			foreach ($links as $link)
				$this->withLink($link, $hateoas);

			return $this;
		}

		/**
		 * Add singolar link to array links
		 *
		 * @param array $link
		 * @param bool $hateoas
		 * @return $this
		 */
		public function withLink(array $link, bool $hateoas = true): BaseHttpResponse
		{
			$processed = array();
			if ($hateoas) {
				$processed = [
					"rel" => array_key_exists(0, $link) ? $link[0] : null,
					"href" => array_key_exists(1, $link) ? $link[1] : null,
					"method" => array_key_exists(2, $link) ? $link[2] : null,
				];
			}
			$this->withContent('links',$processed?:$link);
			return $this;
		}

		/**
		 * @param mixed ...$fields
		 *
		 * @return array
		 */
		public function getOriginal(...$fields) :array {
			if (!empty($fields)) {
				$original = array();
				foreach ($fields as $field) {
					array_key_exists($field, self::$original) ? $original[$field] = self::$original[$field] : NULL;
				}
				return array_filter($original);
			}
			return array_filter(self::$original);
		}
	}