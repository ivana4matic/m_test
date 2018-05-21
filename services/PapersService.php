<?php

namespace Mdpi\services;

class PapersService 
{
	public function getDataFromXml()
	{
		$papers = [];
		$filePath = __DIR__.'/../files/xml_test.xml';
		$file = simplexml_load_file($filePath) or die("Unable to open file!");

		foreach ($file as $record) {

			if ($this->validation((array)$record)) {
				$paper = [];
				$authors = [];
				$keywords = [];

				$paper['doi'] = $record->doi;
				$paper['abstract'] = $record->abstract;
				$paper['title'] = $record->title;
				$paper['publisher_name'] = $record->publisher;
				$paper['publication_year'] = $record->publicationDate;
				$paper['journal_title'] = $record->journalTitle;
				$paper['issue'] = $record->issue;
				$paper['volume'] = $record->volume;
				$paper['core_pdf_url'] = $record->fullTextUrl;

				foreach ($record->authors as $author) {
					$authors = array_merge([(array)$author], $authors);
				}

				$paper['authors'] = $authors;

				foreach ($record->keywords as $keyword) {
					$keywords = array_merge([(array)$keyword], $keywords);
				}

				$paper['keywords'] = $keywords;

				$papers = array_merge([$paper], $papers);
			}
		}

		// array
		return $papers;
	}

	public function getDataFromCsv()
	{
		$filePath = __DIR__.'/../files/csv_test.csv';
		$file = file($filePath) or die("Unable to open file!");
		$columns = explode(',', $file[0]);
		unset($file[0]);
		$papers = [];

		foreach ($file as $line) {
			list($title, $abstract, $doi, $date_published) = explode(',', $line);
			$data['doi'] = $doi;
			if ($this->validation($data)) {
				$paper['title'] = $title;
				$paper['abstract'] = $abstract; 
				$paper['doi'] = $doi;
				$paper['date_published'] = $date_published;
				$papers = array_merge([$paper], $papers);
			}
		}

		// array
		return $papers;
	}

	public function getDataFromJson($apiurl)
	{
		$data = file_get_contents($apiurl);
		$total = json_decode($data, true);
		$items = $total['items'];

		// As the data comes from Scilit I guess there is 
		// a specific reason why DOI is null so I will consider
		// there is an custom rule how DOI is generated
		// otherwise data with invalid DOI shouldn't be processed

		foreach ($items as $key => $item) {
			if (!$this->validation($item)) {
				$items[$key]['doi'] = '10.3456/'.$item['identifier'];
			}
		}

		// array
		return $items;
	}

	private function validation($data)
	{
		if (($data['doi'] == null) or (!preg_match('/^([0-9]{2})\.([0-9]*\/)\w+/', $data['doi']))) {
			return false;
		}

		return true;
	}
}

?>