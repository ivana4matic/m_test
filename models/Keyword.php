<?php

class Keyword
{
	private $db;

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function getAllKeywords()
	{
		$papers = $this->db->query('SELECT * FROM `keywords`');
		return $papers->fetchAll();
	}

	public function setKeyword($name, $doi)
	{
		$statement = $this->db->prepare('INSERT INTO `keywords` (`name`, `paper_doi`) VALUES (:name, :paper_doi)');
		$statement->bindParam(':name', $name);
		$statement->bindParam(':paper_doi', $doi);

		try {
			$result = $statement->execute();
		}
		catch (Exception $e) {
			print_r($e->getMessage());
		}
	}
}