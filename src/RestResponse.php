<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 20/11/18
	 * Time: 23.04
	 */

	namespace Kosmosx\Response;

	use Kosmosx\Response\Contracts\RestResponseInterface;
	use Kosmosx\Response\Traits\ConditionalHeaders;
	use Kosmosx\Response\Traits\EtagHeaders;
	use Kosmosx\Response\Traits\Utilities;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\ResponseHeaderBag;

	class RestResponse extends JsonResponse implements RestResponseInterface 
	{
		use ConditionalHeaders, EtagHeaders, Utilities;

		/**
		 * @param string|NULL $type
		 * @param null        $data
		 * @param int         $status
		 * @param array       $headers
		 * @param bool        $json
		 */
		public function __construct($data = null, int $status = 200, array $headers = array(), ?string $type = null) {
			$this->init($status, $headers);

			if (null != $data)
				$this->withContent($type, $data, true);
		}

		/**
		 * Init response
		 *
		 * @param null  $type
		 * @param int   $status
		 * @param array $headers
		 * @param bool  $json
		 */
		protected function init(int $status_code = 200, array $headers = array()) {
			$this->headers = new ResponseHeaderBag($headers);
			$this->setStatusCode($status_code);
			$this->setProtocolVersion('1.1');
		}
		
		public function withContent(?string $type, $data = array(), bool $override = false): JsonResponse {
			if ($_data = $this->isJSON($data)) {
				$data = $_data;
			}
			unset($_data);

			$content = $this->getContent();
			if (null == $type) {
				if (empty($content))
					$content = $data;
				else
					$content[] = $data;
			} else {
				$this->assignArrayByPath($content, $type, $data, $override);
			}

			$this->setData($content);

			return $this;
		}
		
		public function withContents(array $contents, bool $override = false): JsonResponse {
			foreach ($contents as $type => $content)
				$this->withContent($type, $content, $override);
			return $this;
		}
		
		public function withData($data, bool $override = false, string $type = ''): JsonResponse {
			$type = 'data' . ($type ? '.' . $type : '');

			$this->withContent($type, $data, $override);

			return $this;
		}
		
		public function withError($error, bool $override = false, string $type = ''): JsonResponse {
			$type = 'errors' . ($type ? '.' . $type : '');

			$this->withContent($type, $error, $override);

			return $this;
		}
		
		public function withMeta(array $meta, bool $override = false, string $type = ''): JsonResponse {
			$type = 'meta' . ($type ? '.' . $type : '');

			$this->withContent($type, $meta, $override);
			return $this;
		}
		
		public function withMessage(string $message, bool $override = false, string $type = ''): JsonResponse {
			$type = 'messages' . ($type ? '.' . $type : '');

			$this->withContent($type, $message, $override);
			return $this;
		}
		
		public function withState(bool $override = true, string $type = ''): JsonResponse {
			$type = 'state' . ($type ? '.' . $type : '');

			$state = array_key_exists($this->statusCode, self::$statusTexts) ? self::$statusTexts[$this->statusCode] : 'null';
			
			$this->withContent($type, $state, $override);

			return $this;
		}
		
		public function withIncluded(array $included, bool $override = false, string $type = ''): JsonResponse {
			$type = 'included' . ($type ? '.' . $type : '');

			$this->withContent($type, $included, $override);
			return $this;
		}
		
		public function withValidation(array $validation, bool $override = false, string $type = 'validation'): JsonResponse {
			$type = 'errors' . ($type ? '.' . $type : '');

			$this->withContent($type,  $validation, $override);
			
			return $this;
		}
		
		public function withLinks(array $links): JsonResponse {
			foreach ($links as $key => $link) {
				if (empty($link))
					return $this;

				$link = array_pad($link, 4, null);

				$this->withLink(...$link);
			}

			return $this;
		}
		
		public function withLink(string $href, ?string $resource = null, ?string $method = null, ?array $meta = array()): JsonResponse {
			$link = array();

			if ($resource == null && $method == null && $meta == null)
				$link['self'] = $href;
			else if ($resource != null && $method == null)
				return $this;
			else {
				$link[$resource] = array('href' => $href, 'method' => strtoupper($method));

				if (!empty($meta))
					$link['meta'] = $meta;
			}

			$this->withContent('links', $link);

			return $this;
		}

		public function __toString() {
			$parent = parent::__toString();
			return $parent . $this->getMetadata();
		}
	}