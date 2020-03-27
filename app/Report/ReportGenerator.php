<?php


namespace SSP\Report;


use SSP\HTMLDocument;
use SSP\UrlHandler;

/**
 * Class ReportGenerator
 * @package SSP\Report
 */
class ReportGenerator
{
	public const INFINITY_NESTING_LEVEL = 0;

	/**
	 * @param HTMLDocument $document
	 * @param int $nesting_level_max
	 * @return array
	 * @throws \Exception
	 */
	public static function run(HTMLDocument $document, int $nesting_level_max = self::INFINITY_NESTING_LEVEL): array
	{
		$report = [];
		$passed = [];
		self::generateReport($passed, $report, $document, $nesting_level_max);
		return $report;
	}

	/**
	 * @param array $passed
	 * @param array $report
	 * @param HTMLDocument $document
	 * @param int $nesting_level_max
	 * @param int $nesting_level_current
	 * @return void
	 * @throws \Exception
	 */
	protected static function generateReport(array &$passed, array &$report, HTMLDocument $document, int $nesting_level_max, int $nesting_level_current = 1): void
	{
		if (!in_array($document->document_url, $passed))
			$passed[] = $document->document_url;
		if (!in_array($document->requested_url, $passed))
			$passed[] = $document->requested_url;

		if (isset($report[$document->document_url]))
			return;

		$image_links = self::getImageLinksFromDocument($document);
		$a_links = self::getALinksFromDocument($document);

		$report[$document->document_url] = [];
		$report[$document->document_url]['Image'] = self::prepareClearList($image_links);
		$report[$document->document_url]['Link'] = self::prepareClearList($a_links);

		if ($nesting_level_max != self::INFINITY_NESTING_LEVEL && $nesting_level_current == $nesting_level_max)
			return;
		else {
			foreach ($a_links as $link) {
				if (parse_url($document->document_url)['host'] == parse_url($link)['host'] && !in_array($link, $passed)) {
					$child_document = new HTMLDocument();
					if (!$child_document->loadCurlDocument($link, 10))
						continue;
					self::generateReport($passed, $report, $child_document, $nesting_level_max, $nesting_level_current + 1);
				}
			}
		}
	}

	/**
	 * @param array $list
	 * @return array
	 */
	protected static function prepareClearList(array $list): array
	{
		$clear_list = [];
		foreach ($list as $item) {
			if (!in_array($item, $clear_list)) {
				$clear_list[] = $item;
			}
		}
		return $clear_list;
	}

	/**
	 * @param HTMLDocument $document
	 * @return array
	 * @throws \Exception
	 */
	protected static function getImageLinksFromDocument(HTMLDocument $document): array
	{
		$relative_links = $document->getAttributesByTagName('img', 'src');
		return self::relativeLinksToAbsolute($document->document_url, $relative_links);
	}

	/**
	 * @param HTMLDocument $document
	 * @return array
	 * @throws \Exception
	 */
	protected static function getALinksFromDocument(HTMLDocument $document): array
	{
		$relative_links = $document->getAttributesByTagName('a', 'href');
		return self::relativeLinksToAbsolute($document->document_url, $relative_links);
	}

	/**
	 * @param string $document_url
	 * @param array $relative_links
	 * @return array
	 * @throws \Exception
	 */
	protected static function relativeLinksToAbsolute(string $document_url, array $relative_links): array
	{
		$URL = new UrlHandler($document_url);
		return $URL->getAbsoluteUrls($relative_links);
	}
}
