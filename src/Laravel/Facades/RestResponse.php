<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/08/18
	 * Time: 17.42
	 */
	namespace ServiceResponse\Laravel\Facades;

	use Illuminate\Support\Facades\Facade;

	class RestResponse extends Facade
	{
		protected static function getFacadeAccessor()
		{
			return 'service.response';
		}
	}