<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace ResponseHTTP\Response;

	use ResponseHTTP\Response\Traits\HeadersREST;
	use Carbon\Carbon;
	use Symfony\Component\HttpKernel\Exception\HttpException;

	class ResponseREST extends ResponseService
	{
		use HeadersREST;

		/**
		 * Method response
		 *
		 * @param $content
		 * @param $status
		 * @param array $headers
		 * @param int $options
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function response($content, $status, array $headers = [], $options = 0)
		{
			return response()->json($content, $status, $headers, $options)
				->withHeaders($this->headers)
				->header("cache-control", $this->cacheHeaders)
				->header("last-modified", $this->lastModified ?: Carbon::now()->toRfc7231String())
				->header("etag", $this->etag ?: 'new');
		}
	}