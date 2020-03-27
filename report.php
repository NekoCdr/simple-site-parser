<?php

use SSP\CliWrapper;
use SSP\Report\ReportFS;
use SSP\Report\ReportPrint;
use SSP\UrlHandler;

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH.'/vendor/autoload.php';

try {
	/**
	 * Option list:
	 * domain - print report for specified domain (required)
	 * p      - max records to print (0 - infinity)
	 */
	$options = CliWrapper::getOptions('p:', ['domain:'], ['domain'], ['p' => 0]);

	if ($options['p'] < 0)
		throw new Exception('Max records option must be a natural number');

	$URL = new UrlHandler($options['domain']);
	$report = ReportFS::loadReport($URL->url_components['host']);
	if (!ReportPrint::printReport($report, $options['p']))
		throw new Exception('Report wasn\'t print. Unknown error.');

} catch (Exception $e) {
	echo $e->getMessage(), PHP_EOL;
	exit();
}
