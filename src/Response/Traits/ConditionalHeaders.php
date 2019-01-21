<?php

	namespace ResponseHTTP\Response\Traits;

	trait ConditionalHeaders
	{
		/**
		 * Method to retrive original etag without base64_decode
		 *
		 * @param $response
		 * @return string
		 */
		public function getEtags(): array
		{
			return $this->explodeEtag($this->headers->get('ETag'));
		}

		/**
		 * Method to retrive etag playload of response
		 *
		 * @param $response
		 * @return array
		 */
		public function getEtagPlayload(): array
		{
			$playloads = [];
			$etag = $this->getEtags();
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
		public function setEtagPlayload(string $key = '', string $code = '0x', bool $weak = false) {
			$key = $key ?: str_random(10);
			$etag = base64_encode($code . '.' . $key . '.' . date("Y-m-d H:i:s"));
			return $this->setEtag($etag,$weak);
		}

		/**
		 * Sets multi ETag values.
		 *
		 * example:
		 * ->setEtags(array("etag1","etag2","etagN");
		 *
		 * @param array $etags
		 * @param bool  $weak
		 *
		 * @return $this
		 */
		public function setEtags(array $etags = array(), bool $weak = false) {
			$newEtag = $this->getEtag()? $this->getEtag() . "," :'';

			foreach ($etags as $etag) {
				if (is_string($etag))
					$newEtag .= (true === $weak ? 'W/' : '') . " \"" . $etag . "\", ";
			}

			$this->headers->set('ETag', $newEtag);

			return $this;
		}

		/**
		 * Method to check If-None-Match
		 *
		 * @param Request $request
		 * @param $response
		 * @return bool
		 */
		public function ifNoneMatch($request) {
			$resposeEtags = $this->getEtags();
			$requestEtag = $request->headers->get('If-None-Match');

			foreach ($resposeEtags as $resposeEtag)
				if ($resposeEtag === $requestEtag)
					return $this->reset()->notModified();

			return $this;
		}

		/**
		 * Method to check If-Match
		 *
		 * @param Request $request
		 * @param $response
		 * @return bool
		 */
		public function ifMatch($request) {
			$responseEtags = $this->getEtags();
			$requestEtag = $request->headers->get('If-Match');

			foreach ($responseEtags as $resposeEtag)
				if ($resposeEtag === $requestEtag)
					return $this->reset()->errorPreconditionFailed();

			return $this;
		}

		/**
		 * Method to check If-Modified-Since
		 *
		 * @param \Illuminate\Http\Request $request
		 * @param                          $response
		 *
		 * @return mixed
		 */
		public function ifModifiedSince($request) {
			$requestSince = $request->headers->get('If-Modified-Since');

			if($this->getLastModified() > $requestSince)
				return $this;
			else
				return $this->reset()->notModified();

		}

		/**
		 * Method to check If-Unmodified-Since
		 *
		 * @param \Illuminate\Http\Request $request
		 * @param                          $response
		 *
		 * @return mixed
		 */
		public function ifUnmodifiedSince($request) {
			$requestSince = $request->headers->get('If-Unmodified-Since');

			if($this->getLastModified() < $requestSince)
				return $this;
			else
				return $this->reset()->errorPreconditionFailed();
		}

		private function explodeEtag(string $etags){
			return explode(', ', str_replace('"', '', $etags));
		}
	}