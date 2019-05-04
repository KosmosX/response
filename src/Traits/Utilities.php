<?php

	namespace Kosmosx\Response\Traits;

	use Symfony\Component\HttpFoundation\ResponseHeaderBag;

	trait Utilities
	{
		protected $metadata = null;

		/**
		 * @param string $string
		 * @return bool
		 */
		protected function isJSON($string):?string {
			if(is_string($string)) {
				$decoded = json_decode($string, true);
				if(is_array($decoded) && (json_last_error() == JSON_ERROR_NONE))
					return $decoded;
			}
			return null;
		}

		/**
		 * Init response
		 *
		 * @param null  $type
		 * @param int   $status
		 * @param array $headers
		 * @param bool  $json
		 */
		protected function set($status_code = 200, $headers = array())
		{
			$this->headers = new ResponseHeaderBag($headers);
			$this->setStatusCode($status_code);
			$this->setProtocolVersion('2');

			$metadata = array('init' => [
				'state' => array_key_exists($status_code, self::$statusTexts) ? self::$statusTexts[$status_code] : 'null',
				'code' => (string)$status_code,
				'headers' => $headers ? true : false
			]);

			$this->setMetadata($metadata);
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
				array_key_exists($str[0], $data) ? $found[$str[0]] = $data[$str[0]] : null;

			return $found;
		}

		/**
		 * Get element of array with dot notation
		 *
		 * @param array  $data
		 * @param string $needle
		 * @param string $separator
		 *
		 * @return array|mixed
		 */
		protected function getArrayByPath($data, string $needle, string $separator = '.')
		{
			if(!is_array($data))
				return null;

			$keys = explode($separator, $needle);

			foreach ($keys as $key)
				$data = &$data[$key];

			//return last element
			return $data ?: null;
		}

		/**
		 * Assign to element value with dot notation
		 *
		 * @param array  $data
		 * @param string $needle
		 * @param        $value
		 * @param string $separator
		 */
		protected function assignArrayByPath(array &$data, string $needle, $value, string $separator = '.')
		{
			$keys = explode($separator, $needle);

			foreach ($keys as $key)
				$data = &$data[$key];

			//assign value to last element
			$data = $value;
		}

		/**
		 * Ger data json_encode
		 * or can get only element with key / keys
		 *
		 * @param string ...$_fields
		 *
		 * @return array|mixed
		 */
		public function getData(string ...$_fields): array
		{
			$data = json_decode($this->data, true);

			if (!empty($_fields))
				return $this->find($data, $_fields);

			return is_array($data) ? $data : (array) $data;
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
		 * Ger content json_encode
		 * or can get only element with key / keys
		 *
		 * @param bool $parent
		 *
		 * @return mixed|string
		 */
		public function getContent(string ...$_fields): array
		{
			$content = json_decode($this->content, true);

			if (!empty($_fields))
				return $this->find($content, $_fields);

			return $content ?: array();
		}
	}