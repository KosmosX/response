<?php
	namespace Kosmosx\Response\Contracts;
	
	use Symfony\Component\HttpFoundation\JsonResponse;

	interface RestResponseInterface
	{
		/**
		 * Add element to content response
		 *
		 * @param null|string $type
		 * @param array       $data
		 * @param bool        $override
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withContent(?string $type, $data = array(), bool $override = false): JsonResponse;

		/**
		 * Method to add elements in response
		 *
		 * Example $item:
		 * ['output' => value] or ['output' => value, 'message' => value]
		 *
		 * @param array $contents
		 * @param bool  $override
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withContents(array $contents, bool $override = false): JsonResponse;

		/**
		 * Alias to add Data to content
		 *
		 * @param        $data
		 * @param bool   $override
		 * @param string $type
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withData($data, bool $override = false, string $type = ''): JsonResponse;

		/**
		 * Alias to add Errors to content
		 *
		 * @param        $error
		 * @param bool   $override
		 * @param string $type
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withError($error, bool $override = false, string $type = ''): JsonResponse;

		/**
		 * @param array  $meta
		 * @param bool   $override
		 * @param string $type
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withMeta(array $meta, bool $override = false, string $type = ''): JsonResponse;

		/**
		 * @param string $message
		 * @param bool   $override
		 * @param string $type
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withMessage(string $message, bool $override = false, string $type = ''): JsonResponse;

		/**
		 * @param bool   $override
		 * @param string $type
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withState(bool $override = true, string $type = ''): JsonResponse;

		/**
		 * @param array  $included
		 * @param bool   $override
		 * @param string $type
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withIncluded(array $included, bool $override = false, string $type = ''): JsonResponse;

		/**
		 * @param array  $validation
		 * @param bool   $override
		 * @param string $type
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withValidation(array $validation, bool $override = false, string $type = 'validation'): JsonResponse;

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
		 *    "href": "localhost/posts",
		 *        "method": "GET"
		 *    }
		 *  }
		 *
		 * @param array $links
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withLinks(array $links): JsonResponse;

		/**
		 * @param string      $href
		 * @param null|string $resource
		 * @param null|string $method
		 * @param array|null  $meta
		 *
		 * @return \Symfony\Component\HttpFoundation\JsonResponse
		 */
		public function withLink(string $href, ?string $resource = null, ?string $method = null, ?array $meta = array()): JsonResponse;
	}