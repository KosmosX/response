<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace ServiceResponse\Response;

	use Symfony\Component\HttpKernel\Exception\HttpException;

	class HttpResponse implements HttpResponseInterface
	{
		public function success($content = null, int $status = 200, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, $status, $headers, $type);
		}

		public function successData($content = NULL, int $status = 200, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, $status, $headers, $type);
		}

		public function created($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 201, $headers, $type);
		}

		public function accepted($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 204, $headers, $type);
		}

		public function noContent($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 204, $headers, $type);
		}

		public function error($content = null, int $status = 400, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, $status, $headers, $type);
		}

		public function badRequest($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 400, $headers, $type);
		}

		public function unauthorized($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 401, $headers, $type);
		}

		public function forbidden($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 403, $headers, $type);
		}

		public function notFound($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 404, $headers, $type);
		}

		public function methodNotAllowed($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 405, $headers, $type);
		}

		public function requestTimeout($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 408, $headers, $type);
		}

		public function preconditionFailed($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 412, $headers, $type);
		}

		public function mediaType($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 415,  $headers);
		}

		public function rangeNotSatisfiable($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 416, $headers, $type);
		}

		public function internal($content = null, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, 500, $headers, $type);
		}

		public function serviceUnavailable($content = null, int $status = 500, array $headers = array(), string $type = null) {
			return new BaseHttpResponse($content, $status, $headers, $type);
		}

		public function notModified(array $headers = array(), string $type = null) {
			return new BaseHttpResponse(null, null, 304, $headers, $type);
		}

		public static function exception($content, int $status = 400, array $headers = array(), $code = 0) {
			throw new HttpException($status, $content, null, $headers, $code);
		}
	}