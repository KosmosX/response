<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.04
	 */

	namespace ResponseHTTP\Response;

	use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;
	use Symfony\Component\HttpFoundation\ResponseHeaderBag;

	class BaseHttpResponse extends BaseJsonResponse
	{
		protected $original = array();
		protected $metadata = NULL;

		/**
		 * @param string|NULL $type
		 * @param null        $data
		 * @param int         $status
		 * @param array       $headers
		 * @param bool        $json
		 */
		public function __costructor(string $type = NULL, $data = NULL, int $status = 200, array $headers = array(), bool $json = false)
		{
			$metadata = array('init' => ['type' => $type, 'status' => (string)$status, 'headers' => $headers ? true : false, 'json' => $json]);
			$this->setMetadata($metadata);
			unset($metadata);

			if (NULL === $data)
				$data = array_key_exists($status, self::$statusTexts) ? self::$statusTexts[$status] : new \ArrayObject();

			$this->headers = new ResponseHeaderBag($headers);
			$this->setStatusCode($status);
			$this->setProtocolVersion('1.0');
			$this->withContent($type, $data, false, $json);
		}

		/**
		 * Set a header on the Response.
		 *
		 * @param  string       $key
		 * @param  array|string $values
		 * @param  bool         $replace
		 *
		 * @return $this
		 */
		public function withHeader($key, $values, $replace = true): BaseHttpResponse
		{
			$this->headers->set($key, $values, $replace);
			return $this;
		}

		/**
		 * Add an array of headers to the response.
		 *
		 * @param  \Symfony\Component\HttpFoundation\HeaderBag|array $headers
		 *
		 * @return $this
		 */
		public function withHeaders($headers): BaseHttpResponse
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
		 * Add element to content response
		 *
		 * @param string $type
		 * @param array  $content
		 * @param bool   $override
		 * @param bool   $json
		 *
		 * @return \ResponseHTTP\Response\BaseHttpResponse
		 */
		public function withContent(string $type = NULL, $content = array(), bool $override = false, bool $json = false): BaseHttpResponse
		{
			if ($json)
				$content = json_decode($content, true);

			$data = $this->getData();

			if (!array_key_exists($type, $data) || $override) {
				$data[$type] = $content;
			} else {
				$exist = is_array($data[$type]) ? $data[$type] : array($data[$type]);
				$new = is_array($content) ? $content : array($content);
				$data[$type] = array_merge($exist, $new);
			}

			$this->setData($data);

			return $this;
		}

		/**
		 * Method to add elements in response
		 *
		 * Example $item:
		 * ['output' => value] or ['output' => value, 'message' => value]
		 *
		 * @param array $contents
		 * @param bool  $override
		 *
		 * @return \ResponseHTTP\Response\BaseHttpResponse
		 */
		public function withContents(array $contents, bool $override = false): BaseHttpResponse
		{
			foreach ($contents as $type => $content)
				$this->withContent($type, $content, $override);
			return $this;
		}

		/**
		 * Alias to add Data to content
		 *
		 * @param $data
		 *
		 * @return $this
		 */
		public function withData($data, bool $override = false): BaseHttpResponse
		{
			$this->withContent('data', $data, $override);
			return $this;
		}

		/**
		 * Alias to add Message to content
		 *
		 * @param string $message
		 * @param bool   $override
		 *
		 * @return \ResponseHTTP\Response\BaseHttpResponse
		 */
		public function withMessage(string $message, bool $override = false): BaseHttpResponse {
			$this->withContent('message', $message, $override);
			return $this;
		}

		/**
		 * Alias to add Included to content
		 *
		 * @param array $message
		 * @param bool   $override
		 *
		 * @return \ResponseHTTP\Response\BaseHttpResponse
		 */
		public function withIncluded(array $included, bool $override = false): BaseHttpResponse {
			$this->withContent('included', $included, $override);
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
		 * @param bool  $hateoas
		 *
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
		 * @param bool  $hateoas
		 *
		 * @return $this
		 */
		public function withLink(array $link, bool $hateoas = true): BaseHttpResponse
		{
			if ($hateoas) {
				list($processed['rel'], $processed['href'], $processed['method']) = array_pad(array_values($link), 3, '');
				$link = $processed;
				unset($processed);
			}

			$this->withContent('links', $link, false);

			return $this;
		}

		/**
		 * Set metadata of response
		 *
		 * @param array $values
		 */
		public function setMetadata(array $values) :void
		{
			$metadata = json_decode($this->metadata, true);

			foreach ($values as $key => $value)
				$metadata[$key] = $value;

			$this->metadata = json_encode($metadata, JSON_FORCE_OBJECT);
		}

		/**
		 * Ger metadata
		 * Can get only element with key / keys
		 *
		 * @param mixed ...$fields
		 *
		 * @return array
		 */
		public function getMetadata(string ...$_fields): array
		{
			$metadata = json_decode($this->metadata, true);

			if (!empty($_fields))
				return $this->find($metadata, $_fields);

			return $metadata;
		}

		/**
		 * Ger data json_encode
		 * or can get only element with key / keys
		 *
		 * @param string ...$_fields
		 *
		 * @return array|mixed
		 */
		public function getData(string ...$_fields) :array
		{
			$data = json_decode($this->data, true);
			if (!empty($_fields))
				return $this->find($data, $_fields);

			return $data ? : array();
		}

		/**
		 * Ger content json_encode
		 * or can get only element with key / keys
		 *
		 * @param bool $parent
		 *
		 * @return mixed|string
		 */
		public function getContent(string ...$_fields) :array
		{
			$content = json_decode($this->content, true);

			if (!empty($_fields))
				return $this->find($content, $_fields);

			return $content ? : array();
		}

		/**
		 * Find keys in array data and return only element found
		 *
		 * @param array  $data
		 * @param bool   $json
		 * @param string ...$_gets
		 *
		 * @return array
		 */
		protected function find($data = array(), ...$_gets): array
		{
			$found = array();
			foreach ($_gets as $str)
				array_key_exists($str[0], $data) ? $found[$str[0]] = $data[$str[0]] : NULL;

			return $found;
		}

		/**
		 * Reset response object
		 *
		 * @return $this
		 */
		public function reset(): BaseHttpResponse
		{
			$this->setJson("");
			return $this;
		}
	}