<?php

include_once __DIR__.'/../libs/Migration.php';

class Create_1_PapersTable extends Migration
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
			return print_r("Table 'papers' already exists in the database.\n");
		}

		$result = $this->db->query('CREATE TABLE papers (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			identifier TEXT,
			doi TEXT,
			pmid TEXT,
			title TEXT,
			type TEXT,
			abstract TEXT,
			issue TEXT,
			volume TEXT,
			licence TEXT,
			journal_id TEXT,
			journal_title TEXT,
			publication_year TEXT,
			publisher_name TEXT,
			pdf_url TEXT,
			core_pdf_url TEXT,
			unpaywall_pdf_url TEXT,
			first_page TEXT,
			last_page TEXT
		)');

		if ($result) {
			echo "Migration for creating papers table run successfully.\n";
		} else {
			echo "Migration for creating papers table failed!\n";
		}
	}

	private function checkIfTableExists()
	{
		try {
			$this->db->query("SELECT 1 FROM `papers` LIMIT 1");
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

}

?>