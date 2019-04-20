<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.04
	 */

	namespace ServiceResponse\Response;

	use ServiceResponse\Response\Traits\ConditionalHeaders;
	use ServiceResponse\Response\Traits\EtagHeaders;
	use ServiceResponse\Response\Traits\Utilities;
	use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;
	use Symfony\Component\HttpFoundation\ResponseHeaderBag;

	class BaseHttpResponse extends BaseJsonResponse
	{
		use ConditionalHeaders, EtagHeaders, Utilities;

		/**
		 * @param string|NULL $type
		 * @param null $data
		 * @param int $status
		 * @param array $headers
		 * @param bool $json
		 */
		public function __construct(?string $type, $data = null, int $status = 200, array $headers = array())
		{
			$this->set($type, $status, $headers);

			if (null === $data)
				$data = array_key_exists($status, self::$statusTexts) ? self::$statusTexts[$status] : new \ArrayObject();

			$this->withContent($type, $data, false);
		}

		/**
		 * Add element to content response
		 *
		 * @param string $type
		 * @param array $content
		 * @param bool $override
		 * @param bool $json
		 *
		 * @return \ServiceResponse\Response\BaseHttpResponse
		 */
		public function withContent(?string $type, $data = array(), bool $override = false): BaseHttpResponse
		{
			if ($this->isJSON($data))
				$data = json_decode($data, true);

			$content = $this->getContent();

			if (null == $type)
				$content[] = $data;
			else if (null === ($exist = $this->getArrayByPath($data, $type)) || $override) {
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
		public function withHeader($key, $values, $replace = true): BaseHttpResponse
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
		 * Method to add elements in response
		 *
		 * Example $item:
		 * ['output' => value] or ['output' => value, 'message' => value]
		 *
		 * @param array $contents
		 * @param bool $override
		 *
		 * @return \ServiceResponse\Response\BaseHttpResponse
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
		 * @param bool $override
		 *
		 * @return \ServiceResponse\Response\BaseHttpResponse
		 */
		public function withMessage(string $message, bool $override = false): BaseHttpResponse
		{
			$this->withContent('message', $message, $override);
			return $this;
		}

		/**
		 * Alias to add Included to content
		 *
		 * @param array $message
		 * @param bool $override
		 *
		 * @return \ServiceResponse\Response\BaseHttpResponse
		 */
		public function withIncluded(array $included, bool $override = false): BaseHttpResponse
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
		 * @return \ServiceResponse\Response\BaseHttpResponse
		 */
		public function withValidation(array $validation, bool $override = false): BaseHttpResponse
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