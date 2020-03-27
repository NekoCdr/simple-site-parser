<?php

use SSP\CliWrapper;
use SSP\ErrorHandler;
use SSP\HTMLDocument;
use SSP\Report\ReportGenerator;
use SSP\Report\ReportFS;
use SSP\UrlHandler;

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH.'/vendor/autoload.php';

libxml_use_internal_errors(true);

try {
	/**
	 * Option list:
	 * url - requested URL (required)
	 * r   - max HTTP redirects (0 - disable redirects)
	 * n   - nesting level for recursive parsing (0 - infinity)
	 */
	$options = CliWrapper::getOptions('r:n:', ['url:'], ['url'], ['r' => 10, 'n' => 0]);

	$URL = new UrlHandler($options['url']);
	$doc = new HTMLDocument();
	if (!$doc->loadCurlDocument($URL->url, $options['r'])) {
		if ($doc->http_code == 308 && $doc->redirect_count == $doc->max_redirects)
			throw new Exception("Document wasn't load. Too many redirects.");
		else
			throw new Exception("Document wasn't load. HTTP code: {$doc->http_code}");
	}
	$report = ReportGenerator::run($doc, $options['n']);
	$report_path = ReportFS::saveReport($report, $URL->url_components['host']);

	echo 'Report saved to: ' . CliWrapper::CL_YELLOW . $report_path . CliWrapper::CL_DEFAULT, PHP_EOL;

	if (!empty(libxml_get_errors()))
		echo ErrorHandler::saveLibxmlLogs(libxml_get_errors());
} catch (Exception $e) {
	echo $e->getMessage(), PHP_EOL;
	exit();
}
