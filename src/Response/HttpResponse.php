<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace ServiceResponse\Response;

	use ServiceResponse\Response\Traits\ConditionalHeaders;
	use Symfony\Component\HttpKernel\Exception\HttpException;

	class HttpResponse extends BaseHttpResponse implements HttpResponseInterface
	{
		public function success($content = null, int $status = 200, array $headers = array(), bool $json = false) {
			self::__costructor('success', $content, $status, $headers, $json);
			return $this;
		}

		public function successCreated($content = null, int $status = 201, array $headers = array(), bool $json = false) {
			return $this->success($content, $status, $headers, $json);
		}

		public function successAccepted($content = null, int $status = 202, array $headers = array(), bool $json = false) {
			return $this->success($content, $status, $headers, $json);
		}

		public function successNoContent($content = null, int $status = 204, array $headers = array(), bool $json = false) {
			return $this->success($content, $status, $headers, $json);
		}

		public function successData($content = NULL, int $status = 200, array $headers = array(), bool $json = false) {
			self::__costructor('data', $content, $status, $headers, $json);
			return $this;
		}

		public function error($content = null, int $status = 400, array $headers = array(), bool $json = false) {
			self::__costructor('errors', $content, $status, $headers, $json);
			return $this;
		}

		public function errorBadRequest($content = null, int $status = 400, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorUnauthorized($content = null, int $status = 401, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorForbidden($content = null, int $status = 403, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorNotFound($content = null, int $status = 404, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorMethodNotAllowed($content = null, int $status = 405, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorRequestTimeout($content = null, int $status = 408, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorPreconditionFailed($content = null, int $status = 412, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorMediaType($content = null, int $status = 415, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorRangeNotSatisfiable($content = null, int $status = 416, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorInternal($content = null, int $status = 500, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorServiceUnavailable($content = null, int $status = 500, array $headers = array(), bool $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public static function errorException($content, int $status = 400, array $headers = array(), $code = 0) {
			throw new HttpException($status, $content, null, $headers, $code);
		}

		public function notModified(array $headers = array()) {
			self::__costructor(null, array(), 304, $headers);
			return $this;
		}
	}