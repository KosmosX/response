<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.04
	 */

	namespace ResponseHTTP\Response;

	use ResponseHTTP\Response\Traits\ConditionalHeaders;
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
		 * Add element to content response
		 *
		 * @param string $type
		 * @param array  $content
		 * @param bool   $override
		 * @param bool   $json
		 *
		 * @return \ResponseHTTP\Response\BaseHttpResponse
		 */
		public function withContent(string $type = 'content', $content = array(), bool $override = false, bool $json = false) :BaseHttpResponse{

			if (array_key_exists($type,self::$original))
				self::$original[$type] = $override ?  $content : array(self::$original[$type], $content);
			else
				self::$original += array($type => $content);

			$json ? $this->setJson(self::$original) : $this->setData(self::$original);

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
		public function getOriginal(string ...$_fields) :array {
			if (empty($_fields))
				return array_filter(self::$original);

			return $this->search(self::$original,false, $_fields);
		}

		/**
		 * @return mixed
		 */
		public function getData(bool $json = false, string ...$_fields)
		{
			if (!empty($_fields))
				return $this->search($this->data,true, $_fields);

			if($json)
				return $this->data;

			return json_decode($this->data,true);
		}

		/**
		 * Override getContent method
		 *
		 * @param bool $parent
		 *
		 * @return mixed|string
		 */
		public function getContent(bool $json = false, string ...$_fields)
		{
			if (!empty($_fields))
				return $this->search($this->content,true, $_fields);

			if($json)
				return $this->content;
			return json_decode($this->content,true);
		}

		/**
		 * @param array  $data
		 * @param bool   $json
		 * @param string ...$_gets
		 *
		 * @return array
		 */
		protected function search($data = array(), bool $json = false, ...$_gets):array {
			if($json)
				$data = json_decode($data,true);

			$found = array();
			foreach ($_gets as $str) {
				array_key_exists($str[0], $data) ? $found[$str[0]] = $data[$str[0]] : NULL;
			}
			return $found;
		}
	}