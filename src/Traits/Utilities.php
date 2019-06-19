<?php

	namespace Kosmosx\Response\Traits;

	use Kosmosx\Response\Exceptions\RestException;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\ResponseHeaderBag;

	trait Utilities
	{
		protected $metadata = null;

		/**
		 * Ger data json_encode
		 * or can get only element with key / keys
		 *
		 * @param string ...$_fields
		 *
		 * @return array|mixed
		 */
		public function getData(string ...$_fields): array {
			$data = json_decode($this->data, true);

			if (!empty($_fields))
				return $this->find($data, $_fields);

			return is_array($data) ? $data : (array)$data;
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
		protected function find($data = array(), ...$_gets): array {
			$found = array();
			foreach ($_gets as $str)
				array_key_exists($str[0], $data) ? $found[$str[0]] = $data[$str[0]] : null;

			return $found;
		}

		/**
		 * Reset response object
		 *
		 * @return $this
		 */
		public function reset(): JsonResponse {
			$this->init($this->statusCode, $this->headers);
			$this->setJson("");
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
		public function setHeader($key, $values, $replace = true): JsonResponse {
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
		public function setHeaders($headers): JsonResponse {
			if ($headers instanceof HeaderBag)
				$headers = $headers->all();
			else if (is_array($headers))
				foreach ($headers as $key => $value) {
					$this->headers->set($key, $value);
				}
			else
				return new RestException("header parameter is not valid");

			return $this;
		}

		/**
		 * Ger metadata
		 * Can get only element with key / keys
		 *
		 * @param mixed ...$fields
		 *
		 * @return array
		 */
		public function getMetadata(string ...$_fields): array {
			$metadata = json_decode($this->metadata, true);

			if (!empty($_fields))
				return $this->find($metadata, $_fields);

			return $metadata;
		}

		/**
		 * @param array $values
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function setMetadata(array $values): JsonResponse {
			$metadata = json_decode($this->metadata, true);

			foreach ($values as $key => $value)
				$metadata[$key] = $value;

			$this->metadata = json_encode($metadata, JSON_FORCE_OBJECT);

			return $this;
		}

		/**
		 * Ger content json_encode
		 * or can get only element with key / keys
		 *
		 * @param string ...$_fields
		 *
		 * @return array
		 */
		public function getContent(string ...$_fields): array {
			$content = json_decode($this->content, true);

			if (!empty($_fields))
				return $this->find($content, $_fields);

			return $content ?: array();
		}

		/**
		 * @param $string
		 *
		 * @return null|string
		 */
		protected function isJSON($string): ?string {
			if (is_string($string)) {
				$decoded = json_decode($string, true);
				if (is_array($decoded) && (json_last_error() == JSON_ERROR_NONE))
					return $decoded;
			}
			return null;
		}

		/**
		 * Get last element of array with dot notation
		 *
		 * @param array  $data
		 * @param string $needle
		 * @param string $separator
		 *
		 * @return array|mixed
		 */
		protected function getArrayByPath(array $data, ?string $needle, string $separator = '.') {
			$keys = explode($separator, $needle);

			foreach ($keys as $key) {
				if (end($keys) === $key && array_key_exists($key, $data))
					$get = $data[$key];
			}

			return isset($get) ? $get : null;
		}

		/**
		 * Assign value to element of array with dot notation
		 *
		 * @param array  $content
		 * @param string $needle
		 * @param        $data
		 * @param bool   $override
		 * @param string $separator
		 */
		protected function assignArrayByPath(array &$content, string $needle, $data, bool $override = false, string $separator = '.') {
			$keys = explode($separator, $needle);

			foreach ($keys as $key) {
				if (end($keys) !== $key) {
					$content = &$content[$key];
					continue;
				}
			}

			if (is_array($content)) {
				if (false === array_key_exists($key, $content))
					$content[$key] = $data;
				else
					$content[$key] = $override ? $data : array_merge((array)$content[$key], (array)$data);
			} else {
				$data = array($key => $data);

				if (null != $content && true === $override)
					$content = [$content, $data];
				else
					$content = $data;
			}
		}
	}
