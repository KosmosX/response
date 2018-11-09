<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/11/18
	 * Time: 13.42
	 */

	namespace ResponseHTTP\Response\Traits;

	Trait ModelREST
	{
		private static $links = [];

		abstract static function bootlLinks();

		public static function setLinks(array $links) :array
		{
			self::$links = $links;
			return self::getLinks();
		}

		public static function getLinks() :array {
			return self::$links;
		}
	}