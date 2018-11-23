<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace ResponseHTTP\Response;

	use Symfony\Component\HttpKernel\Exception\HttpException;

	class ResponseService extends ResponseAbstract implements ResponseInterface
	{
		public function data($content = "", $status = 200, array $headers = array(), $options = false) {
			$this->dispatcher('data', $content, $status, $headers, $options);
			return $this;
		}

		public function notModified(array $headers = array(), $options = false) {
			return $this->response([], 304, $headers, $options);
		}

		public function success($content = 'OK', $status = 200, array $headers = array(), $options = false) {
			$this->dispatcher('success', $content, $status, $headers, $options);
			return $this;
		}

		public function successCreated($content = 'Resource Created', $status = 201, array $headers = array(), $options = false) {
			return $this->success($content, $status, $headers, $options);
		}

		public function successAccepted($content = 'Request Accepted', $status = 202, array $headers = array(), $options = false) {
			return $this->success($content, $status, $headers, $options);
		}

		public function successNoContent($content = 'No Content response', $status = 204, array $headers = array(), $options = false) {
			return $this->success($content, $status, $headers, $options);
		}

		public function error($content = "Generic Error", int $status = 400, array $headers = array(), $options = false) {
			$this->dispatcher('error', $content, $status, $headers, $options);
			return $this;
		}

		public function errorBadRequest($content = "Bad Request", int $status = 400, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorUnauthorized($content = "Unauthorized", int $status = 401, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorForbidden($content = "Forbidden", int $status = 403, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorNotFound($content = "Not Found", int $status = 404, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorMethodNotAllowed($content = "Method Not Allowed", int $status = 405, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorRequestTimeout($content = "Error Request Timeout", int $status = 408, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorPreconditionFailed($content = "Error Precondition Failed", int $status = 412, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorMediaType($content = "Error Media Type", int $status = 415, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorRangeNotSatisfiable($content = "Error Range Not Satisfiable", int $status = 416, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorInternal($content = "Internal Error", int $status = 500, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorServiceUnavailable($content = "Service Request is Unavailable", int $status = 500, array $headers = array(), $options = false) {
			return $this->error($content, $status, $headers, $options);
		}

		public function errorException($content, $status = 400, array $headers = array(), $code = 0) {
			throw new HttpException($status, $content, null, $headers, $code);
		}
	}