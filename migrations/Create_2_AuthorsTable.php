<?php

namespace Mdpi\migrations;

use Mdpi\libs\Migration;

class Create_2_AuthorsTable extends Migration
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
			return print_r("Table 'authors' already exists in the database.\n");
		}

		$result = $this->db->query('CREATE TABLE authors (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name TEXT,
			email TEXT,
			affiliation TEXT,
			paper_doi TEXT
		)');

		if ($result) {
			echo "Migration for creating authors table run successfully.\n";
		} else {
			echo "Migration for creating authors table failed!\n";
		}
	}

	private function checkIfTableExists()
	{
		try {
			$this->db->query("SELECT 1 FROM `authors` LIMIT 1");
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}