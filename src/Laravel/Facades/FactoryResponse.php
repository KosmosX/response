<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/08/18
	 * Time: 17.42
	 */
	namespace Kosmosx\Laravel\Facades;

	use Illuminate\Support\Facades\Facade;

	class FactoryResponse extends Facade
	{
		protected static function getFacadeAccessor()
		{
			return 'factory.response';
		}
	}