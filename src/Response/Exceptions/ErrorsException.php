<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 16/09/18
	 * Time: 21.33
	 */
	namespace ResponseHTTP\Response\Exceptions;

	use Symfony\Component\HttpKernel\Exception\HttpException;
	use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

	class ErrorsException extends \Exception {

		/**
		 * Symfony default exceptions
		 *
		 * @var array
		 */
		private static $exceptions = array(
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
		 * Throw HttpResponse
		 *
		 * @param       $content
		 * @param int   $status
		 * @param array $headers
		 * @param int   $code
		 *
		 * @return mixed
		 */
		public static function http ($content, $status = 400, array $headers = [], $code = 0) {
			throw new HttpException($status, $content, null, $headers, $code);
		}

		/**
		 * Throw default symfony exceptions
		 *
		 * example:
		 * return ErrorsException::defaultExceptions('AccessDeniedHttpException');
		 *
		 * @param string $class
		 * @param mixed  ...$constructor (parameters of class constructor
		 */
		public static function defaultExceptions (string $class, ...$constructor) {
			if (in_array($class, self::$exceptions)) {
				$exception = "Symfony\Component\HttpKernel\Exception\\" . $class;
				throw new $exception(...$constructor);
			}
		}
	}