<?php

	namespace ResponseHTTP\Response\Traits;

	use Carbon\Carbon;
	use Illuminate\Http\JsonResponse;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\Support\Facades\Crypt;

	trait ConditionalHeaders
	{
		protected $lastModified;
		protected $etag;

		/**
		 * @param Carbon $data
		 * @return $this
		 */
		public function setLastModified(Carbon $data): object
		{
			$this->lastModified = $data->toRfc7231String();
			return $this;
		}

		/**
		 * @param $response
		 * @return string
		 */
		public function getLastModified($response): string
		{
			if ($response instanceof JsonResponse || $response instanceof Response)
				return $response->headers->get('last-modified');
			return '';
		}

		/**
		 * Method for generate unique etag string and set it in header response
		 * playload: code.key.datatime
		 * -code is optional if you would generate etag with different code
		 * -key is a string, for default is random 10 chars
		 * -datatime is Carbon now result with 'Y-m-d_H:i:s:u' format
		 *
		 * example results:
		 * encode => MHguVDFOMFRlZkFJSC4yMDE4LTExLTEwXzE1OjI3OjQ0OjAzMzI5OA==
		 * decode => 0x.T1N0TefAIH.2018-11-10_15:27:44:033298
		 *
		 * @param string $code
		 * @return $this|object
		 */
		public function generateEtag(string $key = '', string $code = '0x'): object
		{
			$key = $key ?: str_random(10);
			$time = Carbon::now()->format('Y-m-d_H:i:s:u');

			$etag = "\"" . base64_encode($code . '.' . $key . '.' . $time) . "\"";
			return $this->setEtag($etag);
		}

		/**
		 * Method to retrive etag playload of response
		 *
		 * @param $response
		 * @return array
		 */
		public function getPlayloadEtag($response): array
		{
			$playloads = [];
			$etag = $this->getEtag($response);
			foreach ($etag as $item) {
				list($code, $key, $timestamp) = explode('.', base64_decode($item));
				$playload = [
					"code" => $code,
					"key" => $key,
					"timestamp" => $timestamp
				];
				array_push($playloads, $playload);
			}
			return $playloads;
		}

		/**
		 * Method to retrive original etag without base64_decode
		 *
		 * @param $response
		 * @return string
		 */
		public function getEtag($response): array
		{
			if ($response instanceof JsonResponse || $response instanceof Response)
				return explode(', ', str_replace('"', '', $response->headers->get('etag')));
			return [];
		}

		/**
		 * Set etag
		 *
		 * @param string $etag
		 * @return $this
		 */
		public function setEtag(string $etag): object
		{
			$this->etag = $etag;
			return $this;
		}

		/**
		 * @param Request $request
		 * @param $response
		 * @return bool
		 */
		public function ifNoneMatch(Request $request, $response): object
		{
			$resposeEtag = $this->getEtag($response);
			$requestEtag = $request->header('If-None-Match');

			return $requestEtag === $resposeEtag ? $this->notModified() : $response;
		}

		/**
		 * @param Request $request
		 * @param $response
		 * @return bool
		 */
		public function ifMatch(Request $request, $response): object
		{
			$matched = false;
			$etag = $this->getEtag($response);
			foreach ($etag as $item)
				$matched = $request->header('If-Match') === $this->getEtag($response) ? true : $matched;

			if (!$matched) {
				if ($request->method() == 'GET')
					return $this->forced()->errorRangeNotSatisfiable();
				else
					return $this->forced()->errorPreconditionFailed();
			}
			return $response;
		}

		/**
		 * Method to check If-Modified-Since
		 *
		 * @param \Illuminate\Http\Request $request
		 * @param                          $response
		 *
		 * @return mixed
		 */
		public function ifModifiedSince(Request $request, $response) {
			$responseSince = $this->getLastModified($response);
			$requestSince = $request->header('If-Modified-Since');

			if ($responseSince > $requestSince)
				return $this->response('', 200);
			else
				return $this->notModified();
		}

		/**
		 * Method to check If-Unmodified-Since
		 *
		 * @param \Illuminate\Http\Request $request
		 * @param                          $response
		 *
		 * @return mixed
		 */
		public function ifUnmodifiedSince(Request $request, $response) {
			$responseSince = $this->getLastModified($response);
			$requestSince = $request->header('If-Unmodified-Since');

			if ($responseSince > $requestSince)
				return $this->forced()->errorPreconditionFailed();
			else
				return $response;
		}
	}