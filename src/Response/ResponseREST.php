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
			$this->etag ?: $this->generateEtag();
			$headers += [
				"cache-control" => $this->cacheHeaders,
				"last-modified" => $this->lastModified ?: Carbon::now()->toRfc7231String(),
				"etag" => $this->etag
			];

			return parent::response($content,$status,$headers,$options);
		}
	}