<?php

	namespace ResponseHTTP\Response\Traits;

	trait CacheHeaders
	{
		/**
		 * @param string $name
		 */
		private function cacheProcesor(string $name)
		{
			if (array_key_exists('cache-control', $this->headers))
				$this->headers['cache-control'] .= ', ' . $name;
			else
				array_set($this->headers,'cache-control', $name);
		}

		/**
		 * @return $this
		 */
		public function setCachePrivate(): object
		{
			$this->cacheProcesor('private');
			return $this;
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
	}