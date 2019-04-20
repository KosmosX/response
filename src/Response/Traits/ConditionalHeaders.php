<?php

	namespace ServiceResponse\Response\Traits;

	use Symfony\Component\HttpFoundation\Response as BaseResponse;

	trait ConditionalHeaders
	{
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
	}