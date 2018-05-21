<?php

include_once __DIR__.'/../libs/Migration.php';

class Create_3_KeywordsTable extends Migration
{
	private $db;

	public function __construct()
	{
		$this->db = $this->getDB();
	}

	public function up()
	{
		$tableExists = $this->checkIfTableExists();
		
		if ($tableExists) {
			return print_r("Table 'keywords' already exists in the database.\n");
		}

		$result = $this->db->query('CREATE TABLE keywords (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name TEXT,
			paper_doi TEXT
		)');

		if ($result) {
			echo "Migration for creating keywords table run successfully.\n";
		} else {
			echo "Migration for creating keywords table failed!\n";
		}
	}

	private function checkIfTableExists()
	{
		try {
			$this->db->query("SELECT 1 FROM `keywords` LIMIT 1");
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}