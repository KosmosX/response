<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 16/09/18
	 * Time: 21.33
	 */
	namespace Kosmosx\Response\Exceptions;

	use Symfony\Component\HttpKernel\Exception\HttpException;
	use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

	class RestException extends \Exception {
		protected $id;
		protected $links;
		protected $status;
		protected $title;
		protected $detail;
		protected $source;
		protected $meta;
		protected $headers;

		public function __construct($message = "", $code = 0, Throwable $previous = null) {
			parent::__construct($message, $code, $previous );
		}

		public function setTitle($title){
			$this->title = $title;
		}

		public function getTitle(){
			return $this->title;
		}

		public function setSource($source){
			$this->source = $source;
		}

		public function getSource(){
			return $this->source;
		}

		public function setDetail($detail){
			$this->detail = $detail;
		}

		public function getDetail(){
			return $this->detail;
		}

		public function setMeta($meta){
			$this->meta = $meta;
		}

		public function getMeta(){
			return $this->meta;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getId(){
			return $this->id;
		}

		public function setLinks(string $about, array $resources = array()){
			$this->links['about'] = $about;
			if(!empty($resources))
				$this->links['resources'] = $resources;
		}

		public function getLinks(){
			return $this->links;
		}

		public function getStatusCode()
		{
			return $this->status;
		}

		public function setStatusCode(int $status)
		{
			$this->status = $status;
		}

		public function getHeaders()
		{
			return $this->headers;
		}

		public function setHeaders(array $headers)
		{
			$this->headers = $headers;
		}
	}