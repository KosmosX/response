<?php

	namespace ResponseHTTP\Response\Traits;

	use Carbon\Carbon;
	use Illuminate\Http\JsonResponse;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Symfony\Component\HttpFoundation\Response as BaseResponse;
	use Illuminate\Support\Facades\Crypt;

	trait ConditionalHeaders
	{
		/**
		 * @param Carbon $data
		 * @return $this
		 */
		public function setLastModified(Carbon $data = null): object
		{
			if(!$data)
				$data = Carbon::now();

			array_set($this->headers,'last-modified', $data->toRfc7231String());
			return $this;
		}

		/**
		 * @param $response
		 * @return string
		 */
		public function getLastModified(BaseResponse $response): string
		{
			return $response->headers->get('last-modified');
		}

		/**
		 * Method to retrive original etag without base64_decode
		 *
		 * @param $response
		 * @return string
		 */
		public function getEtag(BaseResponse $response): array
		{
			return $this->explodeEtag($response->headers->get('etag'));
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
		public function setEtagPlayload(string $key = '', string $code = '0x'): object
		{
			$key = $key ?: str_random(10);
			$time = Carbon::now()->format('Y-m-d_H:i:s:u');

			$etag = "\"" . base64_encode($code . '.' . $key . '.' . $time) . "\"";
			return $this->setEtag($etag);
		}

		/**
		 * Set etag with forced value
		 *
		 * @param string $etag
		 *
		 * @return object
		 */
		public function setEtagForced(string $etag): object
		{
			$this->setEtag($etag);
			return $this;
		}

		/**
		 * Set etag
		 *
		 * @param string $etag
		 * @return $this
		 */
		public function setEtag(string $etag = ''): object
		{
			if($etag)
				array_set($this->headers,'etag',$etag);
			else
				$this->setEtagPlayload();

			return $this;
		}

		/**
		 * Method to check If-None-Match
		 *
		 * @param Request $request
		 * @param $response
		 * @return bool
		 */
		public function ifNoneMatch(Request $request, BaseResponse $response): object
		{
			$resposeEtag = $this->getEtag($response);
			$requestEtag = $request->header('If-None-Match');

			foreach ($requestEtags as $requestEtag)
				if (in_array($requestEtag,$resposeEtag))
					return $this->notModified();

			return $response;
		}

		/**
		 * Method to check If-Match
		 *
		 * @param Request $request
		 * @param $response
		 * @return bool
		 */
		public function ifMatch(Request $request, BaseResponse $response): object
		{
			$responseEtags = $this->getEtag($response);
			$requestEtags = $this->explodeEtag($request->header('If-Match'));

			foreach ($requestEtags as $requestEtag) {
				if (!in_array($requestEtag,$responseEtags)) {
					if ($request->method() == 'GET')
						return $this->forced()->errorRangeNotSatisfiable();
					else
						return $this->forced()->errorPreconditionFailed();
				}
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
			$responseSince = $this->instaceOf($response) ? $this->getLastModified($response) : $response;
			$requestSince = $request->header('If-Modified-Since');

			if($this->instaceOf($response) && $this->getLastModified($response) > $requestSince)
				return $response;
			elseif ($response > $requestSince)
				return $this->forced()->success('true',200);
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
			$responseSince = $this->instaceOf($response) ? $this->getLastModified($response) : $response;
			$requestSince = $request->header('If-Unmodified-Since');

			if($this->instaceOf($response) && $this->getLastModified($response) < $requestSince)
				return $response;
			elseif ($response < $requestSince)
				return $this->forced()->success('true',200);
			else
				return $this->forced()->errorPreconditionFailed();
		}

		private function instaceOf($response) :bool {
			return $response instanceof BaseResponse ? true : false;
		}

		private function explodeEtag(string $etags){
			return explode(', ', str_replace('"', '', $etags));
		}
	}