<?php

	namespace ResponseHTTP\Response\Traits;

	use Carbon\Carbon;

	trait HeadersREST
	{
		private $cacheHeaders;
		private $lastModified;
		private $etag;

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
		public function setCachePrivate()
		{
			$this->cacheProcesor('private');
			return $this;
		}

		/**
		 * @return $this
		 */
		public function setCachePublic()
		{
			$this->cacheProcesor('public');
			return $this;
		}

		/**
		 * @return $this
		 */
		public function setCacheNoStore()
		{
			$this->cacheProcesor('no-store');
			return $this;
		}

		/**
		 * @return $this
		 */
		public function setCacheNoCache()
		{
			$this->cacheProcesor('no-cache');
			return $this;
		}

		/**
		 * @param int $time
		 * @return $this
		 */
		public function setCacheMaxAge(int $time = 3600)
		{
			$this->cacheProcesor('max-age=' . $time);
			return $this;
		}

		/**
		 * @param string $cache
		 * @return $this
		 */
		public function setCache(string $cache)
		{
			$this->cacheProcesor($cache);
			return $this;
		}

		/**
		 * @param Carbon $data
		 * @return $this
		 */
		public function setLastModified(Carbon $data){
			$this->lastModified = $data->toRfc7231String();
			return $this;
		}

		/**
		 * @param string $etag
		 * @return $this
		 */
		public function setEtag(string $etag) {
			$this->etag = $etag;
			return $this;
		}
	}