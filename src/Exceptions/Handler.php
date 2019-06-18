<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 29/11/18
	 * Time: 21.08
	 */

	namespace Kosmosx\Response\Exceptions;

	use Kosmosx\Response\Exceptions\RestException;
	use Kosmosx\Response\RestResponse;
	use Symfony\Component\HttpKernel\Exception\HttpException;

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
		 * Default exceptions
		 *
		 * @var array
		 */
		private static $exceptions = array(
			'RestException',
			'HttpException',
			'AccessDeniedHttpException',
			'BadRequestHttpException',
			'ConflictHttpException',
			'ControllerDoesNotReturnResponseException',
			'GoneHttpException',
			'NotAcceptableHttpException',
			'NotFoundHttpException',
			'PreconditionFailedHttpException',
			'PreconditionRequiredHttpException',
			'ServiceUnavailableHttpException',
			'TooManyRequestsHttpException',
			'UnauthorizedHttpException',
			'UnprocessableEntityHttpException',
			'UnsupportedMediaTypeHttpException',
		);

		/**
		 * Set exception handler with closure or defaut handle function
		 *
		 * @param \Closure|NULL $callable
		 *
		 * @return callable|null
		 */
		public static function setExceptionHandler(\Closure $callable = null)
		{
			if (null === $callable)
				$callable = array(__CLASS__, 'handle');

			self::$prev = set_exception_handler($callable);

			return self::$prev;
		}

		/**
		 * Set handler with previous handler used
		 *
		 * @return callable|null
		 */
		public static function setPrevExceptionHandler()
		{
			if (null === self::$prev)
				self::$prev = set_exception_handler(null);

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
		public static function getPrev()
		{
			return self::$prev;
		}

		/**
		 * Get running handler
		 *
		 * @return mixed
		 */
		public static function getHandler()
		{
			return self::$handler;
		}

		/**
		 * Response handler
		 *
		 * @param \Exception $e
		 *
		 * @return \Kosmosx\Response\HttpResponse
		 */
		public function handle(\Exception $e)
		{
			$status = 400;

			$headers = array();

			$content = array('errors' => array());

			$methodException = $this->_detectMethodException($e);

			if (null != $methodException)
				$this->{$methodException}($e, $content, $status, $headers);
			else
				$this->_defaultException($e, $content);

			$this->_withDebug($e, $content);

			return new RestResponse($content, $status, $headers);
		}

		private function _detectMethodException(\Exception $exception): ?string
		{
			$namespace = get_class($exception);
			$class = substr($namespace, strrpos($namespace, '\\') + 1);
			$name = '_' . lcfirst($class);

			if (in_array($class, self::$exceptions) && method_exists(self::class, $name))
				return $name;

			return null;
		}

		protected function _defaultException(\Exception $e, array &$content): void
		{
			$content['errors'] = array(
				'description' => $e->getMessage(),
				'code' => $e->getCode(),
			);
		}

		protected function _withDebug(\Exception $e, array &$content): void
		{
			$env = ("true" === getenv('RESPONSE_DEBUG') ? true : false);
			if ($env) {
				$content['errors']['debug'] = array_combine(array('file', 'line', 'trace'), array($e->getFile(), $e->getLine(), $e->getTraceAsString()));
			}
		}

		protected function _httpException(\Exception $e, array &$content, int &$status, array &$headers)
		{
			if (!($e instanceof HttpException))
				return $this->_defaultException($content);

			$headers = $e->getHeaders() ?: $headers;
			$status = $e->getStatusCode() ?: $status;
			$content['errors'] = array_add($content['errors'], 'status', $status);
		}

		protected function _restException(\Exception $e, array &$content, int &$status, array &$headers)
		{
			if (!($e instanceof RestException))
				return $this->_defaultException($content);

			$headers = $e->getHeaders() ?: $headers;
			$status = $e->getStatusCode() ?: $status;

			$content['errors'][] = ['status' => $status];

			if ($id = $e->getId())
				$content['errors'][] = ['id'=> $id];

			if ($links = $e->getLinks())
				$content['errors'][] = ['links'=> $links];

			if ($title = $e->getTitle())
				$content['errors'][] = ['title'=> $title];

			if ($source = $e->getSource())
				$content['errors'][] = ['source'=> $source];

			if ($detail = $e->getDetail())
				$content['errors'][] = ['detail'=> $detail];

			if ($meta = $e->getMeta())
				$content['errors'][] = ['meta'=> $meta];
		}
	}