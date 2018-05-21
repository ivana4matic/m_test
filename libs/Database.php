<?php

namespace Mdpi\libs;

use Mdpi\config\Config;

class Database
{
	protected static function getDB()
	{
		static $db = null;

		if ($db === null) {
			$dbData = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
			$db = new \PDO($dbData, Config::DB_USER, Config::DB_PASSWORD);
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}

		return $db;
	}
}

?>
