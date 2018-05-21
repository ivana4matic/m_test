<?php

namespace Mdpi\controllers;

use Mdpi\services\PapersService;
use Mdpi\models\Paper;

class PapersController
{
	private $db;
	private $config;

	public function __construct($pdo, $config)
	{
		$this->db = $pdo;
		$this->config = $config;
	}

	public function store($type)
	{
		$service = new PapersService;

		switch ($type) {
			case 'xml': {
				$papers = $service->getDataFromXml();
				$this->savePapers($papers);
				return include_once(__DIR__.'/../views/xml.html.php');
			}
			case 'csv': {
				$papers = $service->getDataFromCsv();
				$this->savePapers($papers);
				return include_once(__DIR__.'/../views/csv.html.php');
			}
			case 'json': {
				$papers = $service->getDataFromJson($this->config['apiurl']);
				$this->savePapers($papers);
				return include_once(__DIR__.'/../views/json.html.php');
			}
		}
	}

	public function show()
	{
		$paperObj = new Paper($this->db);
		$papers = $paperObj->getAllPapers();

		return include_once(__DIR__.'/../views/all.html.php');
	}

	private function savePapers($papers)
	{
		foreach ($papers as $paper) {
			$newPaper = new Paper($this->db);
			$newPaper->setPaper($paper);
		}
	}
}

