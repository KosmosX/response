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
		/**
		 * Previous handler
		 *
		 * @var
		 */
		protected static $prev;

		/**
		 * Handler running
		 * @var
		 */
		protected static $handler;

		/**
		 * Response handler
		 *
		 * @param \Exception $e
		 *
		 * @return \ResponseHTTP\Response\HttpResponse
		 */
		public function handle(\Exception $e) {
			$status = 400;
			$headers = [];
			$content = [
				'error' => [
					'message' => $e->getMessage(),
					'code' => $e->getCode(),
				],
			];

			if($e instanceof \HttpException) {
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

		/**
		 * Set exception handler with closure or defaut handle function
		 *
		 * @param \Closure|NULL $callable
		 *
		 * @return callable|null
		 */
		public function setExceptionHandler(\Closure $callable = null) {
			if(null === $callable)
				$callable = array(__CLASS__,'handle');

			self::$prev = set_exception_handler($callable);

			return self::$prev;
		}

		/**
		 * Set handler with previous handler used
		 *
		 * @return callable|null
		 */
		public function setPrevExceptionHandler() {
			if(null === self::$prev)
				self::$prev = set_exception_handler(null);

			$handler = self::$handler;
			self::$handler  = self::$prev;
			self::$prev = $handler;

			restore_exception_handler();

			return self::$prev;
		}

		/**
		 * Get previous handler
		 *
		 * @return mixed
		 */
		public function getPrev(){
			return self::$prev;
		}

		/**
		 * Get running handler
		 *
		 * @return mixed
		 */
		public function getHandler(){
			return self::$handler;
		}
	}