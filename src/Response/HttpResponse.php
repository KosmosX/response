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
		public function success($content = null, int $status = 200, array $headers = array()) {
			return new BaseHttpResponse('success', $content, $status, $headers);
		}

		public function created($content = null, array $headers = array()) {
			return new BaseHttpResponse('success', $content, 201, $headers);
		}

		public function accepted($content = null, array $headers = array()) {
			return new BaseHttpResponse('success', $content, 204, $headers);
		}

		public function noContent($content = null, array $headers = array()) {
			return new BaseHttpResponse('success', $content, 204, $headers);
		}

		public function successData($content = NULL, int $status = 200, array $headers = array()) {
			return new BaseHttpResponse('data', $content, $status, $headers);
		}

		public function error($content = null, int $status = 400, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, $status, $headers);
		}

		public function badRequest($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 400, $headers);
		}

		public function unauthorized($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 401, $headers);
		}

		public function forbidden($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 403, $headers);
		}

		public function notFound($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 404, $headers);
		}

		public function methodNotAllowed($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 405, $headers);
		}

		public function requestTimeout($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 408, $headers);
		}

		public function preconditionFailed($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 412, $headers);
		}

		public function mediaType($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 415,  $headers);
		}

		public function rangeNotSatisfiable($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 416, $headers);
		}

		public function internal($content = null, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, 500, $headers);
		}

		public function serviceUnavailable($content = null, int $status = 500, array $headers = array()) {
			return new BaseHttpResponse('errors', $content, $status, $headers);
		}

		public static function exception($content, int $status = 400, array $headers = array(), $code = 0) {
			throw new HttpException($status, $content, null, $headers, $code);
		}

		public function notModified(array $headers = array()) {
			return new BaseHttpResponse(null, null, 304, $headers);
		}
	}