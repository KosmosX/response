<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.01
	 */

	namespace ServiceResponse\Response;


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
		public function success($content = NULL, int $status = 200, array $headers = array(), string $type = null);

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
		public function created($content = NULL, array $headers = array(), string $type = null);

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
		public function accepted($content = NULL, array $headers = array(), string $type = null);

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
		public function noContent($content = NULL, array $headers = array(), string $type = null);

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
		public function successData($content = NULL, int $status = 200, array $headers = array(), string $type = null);

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
		public function notModified(array $headers = array(), string $type = null);

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
		public function error($content = NULL, int $status = 400, array $headers = array(), string $type = null);

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
		public function badRequest($content = NULL, array $headers = array(), string $type = null);

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
		public function unauthorized($content = NULL, array $headers = array(), string $type = null);

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
		public function forbidden($content = NULL, array $headers = array(), string $type = null);

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
		public function notFound($content = NULL, array $headers = array(), string $type = null);

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
		public function methodNotAllowed($content = NULL, array $headers = array(), string $type = null);

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
		public function requestTimeout($content = NULL, array $headers = array(), string $type = null);

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
		public function preconditionFailed($content = NULL, array $headers = array(), string $type = null);

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
		public function mediaType($content = NULL, array $headers = array(), string $type = null);

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
		public function rangeNotSatisfiable($content = NULL, array $headers = array(), string $type = null);

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
		public function internal($content = NULL, array $headers = array(), string $type = null);

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
		public function serviceUnavailable($content = NULL, int $status = 500, array $headers = array(), string $type = null);

		/**
		 * Metod for error exception
		 *
		 * @param       $content
		 * @param int   $status
		 * @param array $headers
		 * @param int   $code
		 */
		public static function exception($content, int $status = 400, array $headers = array(), $code = 0);
	}