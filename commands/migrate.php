<?php

//---------------------------------
// run: php commands/migrate.php
//---------------------------------

$files = scandir(__DIR__.'/../migrations/');

foreach($files as $file) {

	if ( ($file !== '.') && ($file !== '..') ) {
		
		require_once(__DIR__.'/../migrations/'.$file);
		$class = rtrim($file, '.php');
		$migration = new $class();
		$migration->up();
	}
}

?>