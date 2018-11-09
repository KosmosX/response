<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/08/18
	 * Time: 17.42
	 */
	namespace ResponseHTTP\Response;

	use Illuminate\Support\Facades\Facade;

	class ResponseRESTFacade extends Facade
	{
		protected static function getFacadeAccessor()
		{
			return 'service.response.rest';
		}
	}