<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.01
	 */

	namespace ResponseHTTP\Response;


	interface HttpResponseInterface
	{
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
		 * @param int   $status
		 * @param array $headers
		 * @param int   $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function success($content = NULL, int $status = 200, array $headers = array(), bool $json = false);

		/**
		 * Alias success for Resource created
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return mixed
		 */
		public function successCreated($content = NULL, int $status = 201, array $headers = array(), bool $json = false);

		/**
		 * Alias success for Request Accepted
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return mixed
		 */
		public function successAccepted($content = NULL, int $status = 202, array $headers = array(), bool $json = false);

		/**
		 * Alias success for No content response
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return mixed
		 */
		public function successNoContent($content = NULL, int $status = 204, array $headers = array(), bool $json = false);

		/**
		 * Method for data responses
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function successData($content = NULL, int $status = 200, array $headers = array(), bool $json = false);

		/**
		 * Alias for not modified Response
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return JsonResponse
		 */
		public function notModified(array $headers = array());

		/**
		 * Alias error for badRequest
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorBadRequest($content = NULL, int $status = 400, array $headers = array(), bool $json = false);

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
		 * @param int    $status
		 * @param array  $headers
		 * @param bool    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function error($content = NULL, int $status = 400, array $headers = array(), bool $json = false);

		/**
		 * Alias error for unauthorized
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorUnauthorized($content = NULL, int $status = 401, array $headers = array(), bool $json = false);

		/**
		 * Alias error for forbidden
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorForbidden($content = NULL, int $status = 403, array $headers = array(), bool $json = false);

		/**
		 * Alias error for notFound
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorNotFound($content = NULL, int $status = 404, array $headers = array(), bool $json = false);

		/**
		 * Alias error for Method Not Allowed
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorMethodNotAllowed($content = NULL, int $status = 405, array $headers = array(), bool $json = false);

		/**
		 * Alias error for Request Timeout
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorRequestTimeout($content = NULL, int $status = 408, array $headers = array(), bool $json = false);

		/**
		 * Alias error for Precondition Failed
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return JsonResponse
		 */
		public function errorPreconditionFailed($content = NULL, int $status = 412, array $headers = array(), bool $json = false);

		/**
		 * Alias error for MediaType
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorMediaType($content = NULL, int $status = 415, array $headers = array(), bool $json = false);

		/**
		 * Alias error for Range Not Satisfiable
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorRangeNotSatisfiable($content = NULL, int $status = 416, array $headers = array(), bool $json = false);

		/**
		 * Alias error for Internal
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorInternal($content = NULL, int $status = 500, array $headers = array(), bool $json = false);

		/**
		 * Alias error for ServiceUnavailable
		 *
		 * @param string $content
		 * @param int    $status
		 * @param array  $headers
		 * @param int    $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function errorServiceUnavailable($content = NULL, int $status = 500, array $headers = array(), bool $json = false);

		/**
		 * Metod for error exception
		 *
		 * @param       $content
		 * @param int   $status
		 * @param array $headers
		 * @param int   $code
		 */
		public static function errorException($content, int $status = 400, array $headers = array(), $code = 0);
	}