<?php

	namespace ResponseHTTP\Response\Traits;

	use Carbon\Carbon;
	use Illuminate\Http\JsonResponse;
	use Illuminate\Http\Response;
	use Illuminate\Support\Facades\Crypt;

	trait HeadersREST
	{
		private $cacheHeaders;
		private $lastModified;
		private $etag;

		/**
		 * @return $this
		 */
		public function setCachePrivate(): object
		{
			$this->cacheProcesor('private');
			return $this;
		}

		/**
		 * @param string $name
		 */
		private function cacheProcesor(string $name)
		{
			if ($this->cacheHeaders)
				$this->cacheHeaders .= ', ' . $name;
			else
				$this->cacheHeaders .= $name;
		}

		/**
		 * @return $this
		 */
		public function setCachePublic(): object
		{
			$this->cacheProcesor('public');
			return $this;
		}

		/**
		 * @return $this
		 */
		public function setCacheNoStore(): object
		{
			$this->cacheProcesor('no-store');
			return $this;
		}

		/**
		 * @return $this
		 */
		public function setCacheNoCache(): object
		{
			$this->cacheProcesor('no-cache');
			return $this;
		}

		/**
		 * @param int $time
		 * @return $this
		 */
		public function setCacheMaxAge(int $time = 3600): object
		{
			$this->cacheProcesor('max-age=' . $time);
			return $this;
		}

		/**
		 * @param string $cache
		 * @return $this
		 */
		public function setCache(string $cache): object
		{
			$this->cacheProcesor($cache);
			return $this;
		}

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

			$etag = base64_encode($code . '.' . $key . '.' . $time);
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
			if ($response instanceof JsonResponse || $response instanceof Response) {
				$etag = base64_decode($this->getEtag($response));
				list($code, $key, $time) = explode('.', $etag);
				$playload = [
					'code' => $code,
					'key' => $key,
					'time' => $time
				];
				return $playload;
			}
			return [];
		}

		/**
		 * Method to retrive original etag without base64_decode
		 *
		 * @param $response
		 * @return string
		 */
		public function getEtag($response): string
		{
			if ($response instanceof JsonResponse || $response instanceof Response)
				return $response->headers->get('etag');
			return '';
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
	}