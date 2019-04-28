<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace Kosmosx\Response\Factory;

	use Kosmosx\Response\RestResponse;
	use Symfony\Component\HttpKernel\Exception\HttpException;

	class FactoryResponse implements FactoryInterface
	{
		public function success($content = null, int $status = 200, array $headers = array(), string $type = null) {
			return new RestResponse($content, $status, $headers, $type);
		}

		public function successData($content = NULL, int $status = 200, array $headers = array(), string $type = 'data') {
			return new RestResponse($content, $status, $headers, $type);
		}

		public function created($content = null, array $headers = array(), string $type = null) {
			return new RestResponse($content, 201, $headers, $type);
		}

		public function accepted($content = null, array $headers = array(), string $type = null) {
			return new RestResponse($content, 204, $headers, $type);
		}

		public function noContent($content = null, array $headers = array(), string $type = null) {
			return new RestResponse($content, 204, $headers, $type);
		}

		public function error($content = null, int $status = 400, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, $status, $headers, $type);
		}

		public function badRequest($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 400, $headers, $type);
		}

		public function unauthorized($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 401, $headers, $type);
		}

		public function forbidden($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 403, $headers, $type);
		}

		public function notFound($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 404, $headers, $type);
		}

		public function methodNotAllowed($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 405, $headers, $type);
		}

		public function requestTimeout($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 408, $headers, $type);
		}

		public function preconditionFailed($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 412, $headers, $type);
		}

		public function mediaType($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 415,  $headers);
		}

		public function rangeNotSatisfiable($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 416, $headers, $type);
		}

		public function internal($content = null, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, 500, $headers, $type);
		}

		public function serviceUnavailable($content = null, int $status = 500, array $headers = array(), string $type = 'errors') {
			return new RestResponse($content, $status, $headers, $type);
		}

		public function notModified(array $headers = array()) {
			return new RestResponse(null, 304, $headers);
		}

		public static function exception($content, int $status = 400, array $headers = array(), $code = 0) {
			throw new HttpException($status, $content, null, $headers, $code);
		}
	}