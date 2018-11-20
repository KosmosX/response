<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.01
	 */

	namespace ResponseHTTP\Response;


	interface ResponseInterface
	{
		public function response($content, $status, array $headers = [], $options = 0);

		/**
		 * Method for successful responses
		 * If you add data use method ->withData($data);
		 * If you add links use method ->withLinks($links);
		 * return response with content:
		 * [
		 *   "success": $content,
		 *   "data": [] //Always
		 *   "links": [] //if you use method withLinks()
		 * ]
		 *
		 * @param mixed $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function success($content = 'true', $status = 200, array $headers = [], $options = 0);

		/**
		 * Method for data responses
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function data($content = "", $status = 200, array $headers = [], $options = 0);

		/**
		 * Alias for not modified Response
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return JsonResponse
		 */
		public function notModified(array $headers = [], $options = 0);

		/**
		 * Alias error for badRequest
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorBadRequest($content = "Bad Request", int $status = 400, array $headers = [], $options = 0);

		/**
		 * Method for error responses
		 * If you add data use method ->withData($data);
		 * If you add links use method ->withLinks($links);
		 * return response with content:
		 * [
		 *   "error": $content,
		 *   "data": [] //Always
		 *   "links": [] //if you use method withLinks()
		 * ]
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function error($content = "Generic Error", int $status = 400, array $headers = [], $options = 0);

		/**
		 * Alias error for unauthorized
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorUnauthorized($content = "Unauthorized", int $status = 401, array $headers = [], $options = 0);

		/**
		 * Alias error for forbidden
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorForbidden($content = "Forbidden", int $status = 403, array $headers = [], $options = 0);

		/**
		 * Alias error for notFound
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorNotFound($content = "Not Found", int $status = 404, array $headers = [], $options = 0);

		/**
		 * Alias error for Method Not Allowed
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorMethodNotAllowed($content = "Method Not Allowed", int $status = 405, array $headers = [], $options = 0);

		/**
		 * Alias error for Request Timeout
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorRequestTimeout($content = "Error Request Timeout", int $status = 408, array $headers = [], $options = 0);

		/**
		 * Alias error for Precondition Failed
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return JsonResponse
		 */
		public function errorPreconditionFailed($content = "Error Precondition Failed", int $status = 412, array $headers = [], $options = 0);

		/**
		 * Alias error for MediaType
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorMediaType($content = "Error Media Type", int $status = 415, array $headers = [], $options = 0);

		/**
		 * Alias error for Range Not Satisfiable
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorRangeNotSatisfiable ($content = "Error Range Not Satisfiable", int $status = 416, array $headers = [], $options = 0);

		/**
		 * Alias error for Internal
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorInternal($content = "Internal Error", int $status = 500, array $headers = [], $options = 0);

		/**
		 * Alias error for ServiceUnavailable
		 *
		 * @param string $content
		 * @param int $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorServiceUnavailable($content = "Service Request is Unavailable", int $status = 500, array $headers = [], $options = 0);

		/**
		 * Metod for error exception
		 *
		 * @param $content
		 * @param int $status
		 * @param array $headers
		 * @param int $code
		 */
		public function errorException($content, $status = 400, array $headers = [], $code = 0);
	}