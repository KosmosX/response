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
	use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;
	use Symfony\Component\HttpFoundation\ResponseHeaderBag;

	class RestResponse extends BaseJsonResponse
	{
		use ConditionalHeaders, EtagHeaders, Utilities;

		/**
		 * @param string|NULL $type
		 * @param null $data
		 * @param int $status
		 * @param array $headers
		 * @param bool $json
		 */
		public function __construct($data = null, int $status = 200, array $headers = array(), ?string $type = null)
		{
			$this->set($status, $headers);

			if (null != $data)
				$this->withContent($type, $data, true);
		}

		/**
		 * Add element to content response
		 *
		 * @param string $type
		 * @param array $content
		 * @param bool $override
		 * @param bool $json
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withContent(?string $type, $data = array(), bool $override = false): self
		{
			if ($_data = $this->isJSON($data)){
				$data = $_data;
				unset($_data);
			}

			$content = $this->getContent();
			$exist = $this->getArrayByPath($content, $type);

			if (null == $type)
				$content[] = $data;
			else if (null == $exist || $override) {
				$this->assignArrayByPath($content, $type, $data);
			} else {
				if (false === is_array($exist))
					$exist = (array)$exist;
				if (false === is_array($data))
					$data = (array)$data;
				$this->assignArrayByPath($content, $type, array_merge($exist, $data));
			}

			$this->setData($content);

			return $this;
		}

		/**
		 * Set a header on the Response.
		 *
		 * @param string $key
		 * @param array|string $values
		 * @param bool $replace
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
		 * @param bool $override
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
		 * Alias to add Message to content
		 *
		 * @param string $message
		 * @param bool $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withMessage(string $message, bool $override = false): self
		{
			$this->withContent('message', $message, $override);
			return $this;
		}

		/**
		 * Alias to add State to content
		 *
		 * @param string $message
		 * @param bool $override
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
		 * @param bool $override
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
		 * @param bool $override
		 *
		 * @return \Kosmosx\Response\RestResponse
		 */
		public function withValidation(array $validation, bool $override = false): self
		{
			$this->withContent('validationErrors', $validation, $override);
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
		 *
		 * @return $this
		 */
		public function withLinks(array $links, bool $hateoas = true): self
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
		 *
		 * @return $this
		 */
		public function withLink(array $link, bool $hateoas = true): self
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
		 * Reset response object
		 *
		 * @return $this
		 */
		public function reset(): self
		{
			$this->setJson("");
			return $this;
		}
	}