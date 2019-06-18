<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.01
	 */

	namespace Kosmosx\Response\Factory;


	use Kosmosx\Response\Exceptions\RestException;
	use Kosmosx\Response\RestResponse;

	interface FactoryInterface
	{
		/**
		 * Method for successful responses
		 * If you add data use method ->withData($data):RestResponse;
		 * If you add links use method ->withLinks($links):RestResponse;
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
		public static function success($content = null, int $status = 200, array $headers = array(), string $type = null): RestResponse;

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
		public static function created($content = null, array $headers = array(), string $type = null): RestResponse;

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
		public static function accepted($content = null, array $headers = array(), string $type = null): RestResponse;

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
		public static function noContent($content = null, array $headers = array(), string $type = null): RestResponse;

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
		public static function notModified(array $headers = array()): RestResponse;

		/**
		 * Method for error responses
		 * If you add data use method ->withData($data):RestResponse;
		 * If you add links use method ->withLinks($links):RestResponse;
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
		 * @param bool   $json
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
		public static function error($content = null, int $status = 400, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function badRequest($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function unauthorized($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function forbidden($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function notFound($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function methodNotAllowed($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function requestTimeout($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function preconditionFailed($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function mediaType($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		public static function rangeNotSatisfiable($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

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
		/**
		 * @param null   $content
		 * @param array  $headers
		 * @param string $type
		 *
		 * @return mixed
		 */
		public static function internal($content = null, array $headers = array(), string $type = 'errors'): RestResponse;

		/**
		 * @param                 $content
		 * @param int             $code
		 * @param \Throwable|null $previous
		 *
		 * @return mixed
		 */
		public static function throwException($content, $code = 0, \Throwable $previous = null);

		/**
		 * @param                 $content
		 * @param int             $code
		 * @param \Throwable|null $previous
		 *
		 * @return mixed
		 */
		public static function throwExceptionObj($content, $code = 0, \Throwable $previous = null): RestException;
	}