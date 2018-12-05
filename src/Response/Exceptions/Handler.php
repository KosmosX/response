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
		public function handle(\Exception $e)
		{
			$env = (getenv('RESPONSE_DEBUG') === "true" ? true : false);
			$status = 400;
			$headers = array();
			$content = array(
				'error' => [
					'message' => $e->getMessage(),
					'code' => $e->getCode(),
				]
			);

			if ($e instanceof \HttpException) {
				$status = $e->getStatusCode();
				$headers = $e->getHeaders();
				$content['error'] = array_add($content['error'], 'status_code', $status);
			}

			if ($env) {
				$content['error']['debug'] = array_combine(array('file', 'line', 'trace'), array($e->getFile(), $e->getLine(), $e->getTraceAsString()));
			}

			return new HttpResponse($content, $status, $headers, false);
		}

		/**
		 * Set exception handler with closure or defaut handle function
		 *
		 * @param \Closure|NULL $callable
		 *
		 * @return callable|null
		 */
		public function setExceptionHandler(\Closure $callable = NULL)
		{
			if (NULL === $callable)
				$callable = array(__CLASS__, 'handle');

			self::$prev = set_exception_handler($callable);

			return self::$prev;
		}

		/**
		 * Set handler with previous handler used
		 *
		 * @return callable|null
		 */
		public function setPrevExceptionHandler()
		{
			if (NULL === self::$prev)
				self::$prev = set_exception_handler(NULL);

			$handler = self::$handler;
			self::$handler = self::$prev;
			self::$prev = $handler;

			restore_exception_handler();

			return self::$prev;
		}

		/**
		 * Get previous handler
		 *
		 * @return mixed
		 */
		public function getPrev()
		{
			return self::$prev;
		}

		/**
		 * Get running handler
		 *
		 * @return mixed
		 */
		public function getHandler()
		{
			return self::$handler;
		}
	}