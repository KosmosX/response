<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace ResponseHTTP\Response;

	use Symfony\Component\HttpKernel\Exception\HttpException;

	class HttpResponse extends BaseHttpResponse implements HttpResponseInterface
	{
		public function data($content = NULL, $status = 200, array $headers = array(), $json = false) {
			$this->dispatcher('data', $content, $status, $headers, $json);
			return $this;
		}

		public function notModified(array $headers = array(), $json = false) {
			parent::__construct('', 304, $headers, $json);
			return $this;
		}

		public function success($content = null, $status = 200, array $headers = array(), $json = false) {
			$this->dispatcher('success', $content, $status, $headers, $json);
			return $this;
		}

		public function successCreated($content = null, $status = 201, array $headers = array(), $json = false) {
			return $this->success($content, $status, $headers, $json);
		}

		public function successAccepted($content = null, $status = 202, array $headers = array(), $json = false) {
			return $this->success($content, $status, $headers, $json);
		}

		public function successNoContent($content = null, $status = 204, array $headers = array(), $json = false) {
			return $this->success($content, $status, $headers, $json);
		}

		public function error($content = null, int $status = 400, array $headers = array(), $json = false) {
			$this->dispatcher('error', $content, $status, $headers, $json);
			return $this;
		}

		public function errorBadRequest($content = null, int $status = 400, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorUnauthorized($content = null, int $status = 401, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorForbidden($content = null, int $status = 403, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorNotFound($content = null, int $status = 404, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorMethodNotAllowed($content = null, int $status = 405, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorRequestTimeout($content = null, int $status = 408, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorPreconditionFailed($content = null, int $status = 412, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorMediaType($content = null, int $status = 415, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorRangeNotSatisfiable($content = null, int $status = 416, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorInternal($content = null, int $status = 500, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public function errorServiceUnavailable($content = null, int $status = 500, array $headers = array(), $json = false) {
			return $this->error($content, $status, $headers, $json);
		}

		public static function errorException($content, $status = 400, array $headers = array(), $code = 0) {
			throw new HttpException($status, $content, null, $headers, $code);
		}
	}