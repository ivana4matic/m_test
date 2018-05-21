<?php

require_once 'Author.php';
require_once 'Keyword.php';

class Paper
{
	private $db;

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function getAllPapers()
	{
		$papers = $this->db->query('SELECT * FROM `papers`');
		return $papers->fetchAll();
	}

	public function setPaper($data)
	{
		// if paper exists, delete it form db first and then add it with fresh information
		if (count($this->findByDoi($data['doi'])) > 0)  {
			$this->deleteByDoi($data['doi']);
		}

		$statement = $this->db->prepare("INSERT INTO `papers` (`doi`, `title`, `abstract`, `identifier`, `pmid`, `publisher_name`, `publication_year`, `journal_title`, `type`, `volume`, `issue`, `journal_id`, `pdf_url`, `core_pdf_url`, `unpaywall_pdf_url`, `first_page`, `last_page`) VALUES (:doi, :title, :abstract, :identifier, :pmid, :publisher_name, :publication_year, :journal_title, :type, :volume, :issue, :journal_id, :pdf_url, :core_pdf_url, :unpaywall_pdf_url, :first_page, :last_page)");

		$identifier = 'null';

		if (isset($data['identifier'])) {
			$identifier = $data['identifier'];
		}

		$statement->bindParam(':identifier', $identifier);
		$statement->bindParam(':doi', $data['doi']);
		$statement->bindParam(':title', $data['title']);
		$statement->bindParam(':abstract', $data['abstract']);

		$statement = $this->bindParameters($statement, $data, 'pmid');
		$statement = $this->bindParameters($statement, $data, 'publisher_name');
		$statement = $this->bindParameters($statement, $data, 'journal_title');
		$statement = $this->bindParameters($statement, $data, 'type');
		$statement = $this->bindParameters($statement, $data, 'volume');
		$statement = $this->bindParameters($statement, $data, 'issue');
		$statement = $this->bindParameters($statement, $data, 'journal_id');
		$statement = $this->bindParameters($statement, $data, 'publication_year');
		$statement = $this->bindParameters($statement, $data, 'pdf_url');
		$statement = $this->bindParameters($statement, $data, 'core_pdf_url');
		$statement = $this->bindParameters($statement, $data, 'unpaywall_pdf_url');
		$statement = $this->bindParameters($statement, $data, 'first_page');
		$statement = $this->bindParameters($statement, $data, 'last_page');
		
		try {
			$result = $statement->execute();
		}
		catch (Exception $e) {
			print_r($e->getMessage());
		}

		if (isset($data['authors'])) {
			$this->setAuthors($data['authors'], $data['doi']);
		}

		if (isset($data['keywords'])) {
			$this->setKeywords($data['keywords'], $data['doi']);
		}
	}

	public function findByDoi($doi)
	{
		$statement = $this->db->prepare('SELECT * FROM `papers` WHERE `doi`=:doi LIMIT 1');
		$statement->bindParam(':doi', $doi);
		$statement->execute();
		$paper = $statement->fetch(PDO::FETCH_ASSOC);

		if ($paper == false) {
			return [];
		}

		return $paper;
	}

	public function deleteByDoi($doi)
	{
		// delete authors and keywords for the paper first to add fresh information
		$this->deleteKeywordsForDoi($doi);
		$this->deleteAuthorsForDoi($doi);

		$statement = $this->db->prepare('DELETE FROM `papers` WHERE `doi`=:doi');
		$statement->bindParam(':doi', $doi);
		$statement->execute();
	}

	private function bindParameters($statement, $data, $param)
	{
		$val = 'nul';
		if (isset($data[$param])) {
			$val = $data[$param];
		}
		
		$statement->bindParam(':'.$param, $val);
		return $statement;
	}

	private function setAuthors($authors, $doi)
	{
		if (isset($authors[0]['author'])) {
			$autorsGroup = $authors[0]['author'];
		} else {
			$autorsGroup = $authors;
		}
		foreach ($autorsGroup as $author) {
			$newAuthor = new Author($this->db);
			$newAuthor->setAuthor((array)($author), $doi);
		}
	}

	private function setKeywords($data, $doi)
	{
		if (isset($data[0]['keyword'])) { // xml
			$papers = $data[0]['keyword'];
		} else {
			$papers = $data; // json
		}

		foreach ($papers as $keyword) {
			$newKeyword = new Keyword($this->db);
			$newKeyword->setKeyword($keyword, $doi);
		}
	}

	private function deleteKeywordsForDoi($doi)
	{
		$statement = $this->db->prepare('DELETE FROM `keywords` WHERE `paper_doi`=:paper_doi');
		$statement->bindParam(':paper_doi', $doi);
		$statement->execute();
	}

	private function deleteAuthorsForDoi($doi)
	{
		$statement = $this->db->prepare('DELETE FROM `authors` WHERE `paper_doi`=:paper_doi');
		$statement->bindParam(':paper_doi', $doi);
		$statement->execute();
	}
}

?>