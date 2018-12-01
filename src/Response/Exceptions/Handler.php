<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 29/11/18
	 * Time: 21.08
	 */

	namespace ResponseHTTP\Response\Exceptions;

	use ResponseHTTP\Response\HttpResponse;

	class Handler
	{
		public function handle(\Exception $e) {
			$status = 400;
			$headers = [];
			$content = [
				'error' => [
					'message' => $e->getMessage(),
					'code' => $e->getCode(),
				],
			];

			if($e instanceof HttpException) {
				$status = $e->getStatusCode();
				$headers = $e->getHeaders();
				$content['error'] = array_add($content['error'], 'status_code', $status);
			}

			if(getenv('RESPONSE_DEBUG')?:true){
				$content['error'] = array_add($content['error'], 'debug.file', $e->getFile());
				$content['error'] = array_add($content['error'], 'debug.line', $e->getLine());
				$content['error'] = array_add($content['error'], 'debug.trace', $e->getTraceAsString());
			}

			return new HttpResponse($content, $status,$headers,false);
		}

		public function setExceptionHandler(\Closure $callable = null) {
			if(null === $callable)
				$callable = array(__CLASS__,'handle');
			set_exception_handler($callable);
		}

		public function restoreExceptionHandler() {
			restore_exception_handler();
		}
	}