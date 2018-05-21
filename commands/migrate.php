<?php

//---------------------------------
// run: php commands/migrate.php
//---------------------------------

require_once __DIR__.'/../vendor/autoload.php';

use Mdpi\migrations\Create_1_PapersTable;
use Mdpi\migrations\Create_2_AuthorsTable;
use Mdpi\migrations\Create_3_KeywordsTable;

$files = scandir(__DIR__.'/../migrations/');
$namespace = "\\Mdpi\migrations\\";

foreach($files as $file) {

	if ( ($file !== '.') && ($file !== '..') ) {
		
		$migrationClass = $namespace.rtrim($file, '.php');
		$migration = new $migrationClass();
		$migration->up();
	}
}

?>