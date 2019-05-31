<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.04
	 */

	namespace Kosmosx\Response;

	use Kosmosx\Response\Traits\ConditionalHeaders;
	use Kosmosx\Response\Traits\EtagHeaders;
	use Kosmosx\Response\Traits\Utilities;
	use Kosmosx\Response\Exceptions\RestException;
	use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;
	use Symfony\Component\HttpFoundation\ResponseHeaderBag;

	class RestResponse extends BaseJsonResponse
	{
		use ConditionalHeaders, EtagHeaders, Utilities;

		protected $metadata = null;

		/**
		 * @param string|NULL $type
		 * @param null        $data
		 * @param int         $status
		 * @param array       $headers
		 * @param bool        $json
		 */
		public function __construct($data = null, int $status = 200, array $headers = array(), ?string $type = null)
		{
			$this->set($status, $headers);

			if (null != $data)
				$this->withContent($type, $data, true);
		}

		/**
		 * Init response
		 *
		 * @param null  $type
		 * @param int   $status
		 * @param array $headers
		 * @param bool  $json
		 */
		protected function set(int $status_code = 200, array $headers = array())
		{
			$this->headers = new ResponseHeaderBag($headers);
			$this->setStatusCode($status_code);
			$this->setProtocolVersion('1.1');
		}

		/**
		 * Add element to content response
		 *
		 * @param null|string $type
		 * @param array       $data
		 * @param bool        $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withContent(?string $type, $data = array(), bool $override = false): self
		{
			if ($_data = $this->isJSON($data)) {
				$data = $_data;
			}
			unset($_data);

			$content = $this->getContent();

			if (null == $type) {
				if(empty($content))
					$content = $data;
				else
					$content[] = $data;
			} else {
				$exist = $this->getArrayByPath($content, $type);
				if (null == $exist || $override)
					$this->assignArrayByPath($content, $type, $data);
				else
					$this->assignArrayByPath($content, $type, array_merge((array)$exist, (array)$data));

			}

			$this->setData($content);

			return $this;
		}

		/**
		 * Set a header on the Response.
		 *
		 * @param string       $key
		 * @param array|string $values
		 * @param bool         $replace
		 *
		 * @return $this
		 */
		public function withHeader($key, $values, $replace = true): self
		{
			$this->headers->set($key, $values, $replace);
			return $this;
		}

		/**
		 * Add an array of headers to the response.
		 *
		 * @param \Symfony\Component\HttpFoundation\HeaderBag|array $headers
		 *
		 * @return $this
		 */
		public function withHeaders($headers): self
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
		 * Method to add elements in response
		 *
		 * Example $item:
		 * ['output' => value] or ['output' => value, 'message' => value]
		 *
		 * @param array $contents
		 * @param bool  $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withContents(array $contents, bool $override = false): self
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
		public function withData($data, bool $override = false): self
		{
			$this->withContent('data', $data, $override);
			return $this;
		}

		/**
		 * Alias to add Errors to content
		 *
		 * @param $data
		 *
		 * @return $this
		 */
		public function withError($error, bool $override = false): self
		{
			$this->withContent('errors', $error, $override);
			return $this;
		}


		public function withMeta(array $meta, bool $override = false): self
		{
			$this->withContent('meta', $meta, $override);
			return $this;
		}

		/**
		 * Alias to add Message to content
		 *
		 * @param string $message
		 * @param bool   $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withMessage(string $message, bool $override = false): self
		{
			$this->withContent('message', $message, $override);
			return $this;
		}

		/**
		 * @param array $errors
		 * @param bool  $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withErrors(array $errors, bool $override = false): self
		{
			$this->withContent('errors', $errors, $override);
			return $this;
		}

		/**
		 * Alias to add State to content
		 *
		 * @param string $message
		 * @param bool   $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withState(bool $override = true): self
		{
			$state = array_key_exists($this->statusCode, self::$statusTexts) ? self::$statusTexts[$this->statusCode] : 'null';
			$this->withContent('state', $state, $override);

			return $this;
		}

		/**
		 * Alias to add Included to content
		 *
		 * @param array $message
		 * @param bool  $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withIncluded(array $included, bool $override = false): self
		{
			$this->withContent('included', $included, $override);
			return $this;
		}

		/**
		 * Alias to add Validation errors to content
		 *
		 * @param array $message
		 * @param bool  $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withValidation(array $validation, bool $override = false): self
		{
			$this->withContent('errors.validation', $validation, $override);
			return $this;
		}

		/**
		 * Method to adds Links to error or success response
		 * $hateoas make $links to standard of Rest Api
		 *
		 * //Example links array
		 * links = [
		 *      ['localhost/user'],
		 *      ['localhost/posts','post','GET']
		 * ]
		 *
		 * "links": {
		 *    "self": "localhost/user",
		 *    "post": {
		 *  	"href": "localhost/posts",
		 *	    "method": "GET"
		 *    }
		 *  }
		 *
		 * @param array $links
		 * @param bool  $hateoas
		 *
		 * @return $this
		 */
		public function withLinks(array $links): self
		{
			foreach ($links as $key => $link) {
				if(empty($link))
					return $this;

				$link = array_pad($link,4,null);

				$this->withLink(...$link);
			}

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
		public function withLink(string $href, ?string $resource = null, ?string $method = null, ?array $meta = array()): self
		{
			$link = array();

			if ($resource == null && $method == null && $meta == null)
				$link['self'] = $href;
			elseif ($resource != null && $method == null)
					return $this;
			else {
				$link[$resource] = array('href'=>$href,'method'=>strtoupper($method));

				if (!empty($meta))
					$link['meta'] = $meta;
			}

			$this->withContent('data.links', $link, false);

			return $this;
		}

		/**
		 * Set metadata of response
		 *
		 * @param array $values
		 */
		public function setMetadata(array $values): void
		{
			$metadata = json_decode($this->metadata, true);

			foreach ($values as $key => $value)
				$metadata[$key] = $value;

			$this->metadata = json_encode($metadata, JSON_FORCE_OBJECT);
		}

		/**
		 * Reset response object
		 *
		 * @return $this
		 */
		public function reset(): self
		{
			$this->set($this->statusCode, $this->headers);
			$this->setJson("");
			return $this;
		}

		public function __toString()
		{
			$parent = parent::__toString();
			return $parent . $this->getMetadata();
		}
	}