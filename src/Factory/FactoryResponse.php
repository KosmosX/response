<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace Kosmosx\Response\Factory;

	use Kosmosx\Response\Exceptions\RestException;
	use Kosmosx\Response\RestResponse;

	class FactoryResponse implements FactoryInterface
	{
		public static function success($content = null, int $status = 200, array $headers = array(), string $type = null): RestResponse {
			return new RestResponse($content, $status, $headers, $type);
		}

		public static function created($content = null, array $headers = array(), string $type = null): RestResponse {
			return new RestResponse($content, 201, $headers, $type);
		}

		public static function accepted($content = null, array $headers = array(), string $type = null): RestResponse {
			return new RestResponse($content, 202, $headers, $type);
		}

		public static function noContent($content = null, array $headers = array(), string $type = null): RestResponse {
			return new RestResponse($content, 204, $headers, $type);
		}

		public static function error($content = null, int $status = 400, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, $status, $headers, $type);
		}

		public static function badRequest($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 400, $headers, $type);
		}

		public static function unauthorized($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 401, $headers, $type);
		}

		public static function forbidden($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 403, $headers, $type);
		}

		public static function notFound($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 404, $headers, $type);
		}

		public static function methodNotAllowed($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 405, $headers, $type);
		}

		public static function requestTimeout($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 408, $headers, $type);
		}

		public static function preconditionFailed($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 412, $headers, $type);
		}

		public static function mediaType($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 415, $headers, $type);
		}

		public static function rangeNotSatisfiable($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 416, $headers, $type);
		}

		public static function internal($content = null, array $headers = array(), string $type = 'errors'): RestResponse {
			return new RestResponse($content, 500, $headers, $type);
		}

		public static function notModified(array $headers = array()): RestResponse {
			return new RestResponse(null, 304, $headers);
		}

		public static function throwException($content, $code = 0, \Throwable $previous = null) {
			throw new RestException($content, $code, $previous);
		}

		public static function throwExceptionObj($content, $code = 0, \Throwable $previous = null): RestException {
			return new RestException($content, $code, $previous);
		}
	}