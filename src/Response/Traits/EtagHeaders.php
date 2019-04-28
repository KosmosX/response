<?php

	namespace Kosmosx\Response\Traits;

	trait EtagHeaders
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
			$etags = $this->getEtags();

			foreach ($etags as $etag) {
				list($code, $key, $timestamp) = explode('.', base64_decode($etag));
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
			$newEtag = base64_encode($code . '.' . $key . '.' . date("Y-m-d H:i:s"));

			$etags = $this->headers->get('ETag') ? : '';

			$etags .= (true === $weak ? 'W/ ' : '') . "\"" . $newEtag . "\",";

			$this->headers->set('ETag', $etags);

			return $this;
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
		public function setEtags(array $etags = array(), string $code = '0x', bool $weak = false) {

			foreach ($etags as $etag) {
				if (is_string($etag))
					$this->setEtagPlayload($etag, $code, $weak);
			}

			return $this;
		}

		private function explodeEtag(string $etags){
			return explode(',', trim(str_replace('"', '', $etags)),-1);
		}
	}