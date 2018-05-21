<?php

namespace Mdpi\models;

class Author
{
	private $db;

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function getAllAuthors()
	{
		$papers = $this->db->query('SELECT * FROM `authors`');
		return $papers->fetchAll();
	}

	public function setAuthor($data, $doi)
	{
		$statement = $this->db->prepare('INSERT INTO `authors` (`name`, `email`, `affiliation`, `paper_doi`) VALUES (:name, :email, :affiliation, :paper_doi)');

		$statement->bindParam(':name', $data['name']);
		$statement->bindParam(':paper_doi', $doi);
		$statement->bindParam(':email', $data['email']);
		$statement->bindParam(':affiliation', $data['affiliation']);

		try {
			$result = $statement->execute();
		}
		catch (Exception $e) {
			print_r($e->getMessage());
		}
	}
}