<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 16/09/18
	 * Time: 21.33
	 */
	namespace ResponseHTTP\Response\Exceptions;

	use ResponseHTTP\Response\HttpResponse;

	class ErrorsException extends \Exception {
		/**
		 * Exception response call HttpResponse
		 *
		 * @param       $content
		 * @param int   $status
		 * @param array $headers
		 * @param int   $code
		 *
		 * @return mixed
		 */
		public static function exception ($content, $status = 400, array $headers = [], $code = 0) {
			return HttpResponse::errorException($content, $status, null, $headers, $code);
		}
	}