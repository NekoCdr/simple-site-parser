<?php


use SSP\CliWrapper;

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH.'/vendor/autoload.php';

try {
	echo 'Welcome to Simple Site Parser!', PHP_EOL;
	echo 'This is a simple site parser. xD', PHP_EOL;
	echo PHP_EOL;
	echo 'Use ' . CliWrapper::CL_YELLOW . 'parse.php' . CliWrapper::CL_DEFAULT . ' for parse page and save the report to a file.', PHP_EOL;
	echo "--url\t\t - parse requested URL (required)", PHP_EOL;
	echo "-r\t\t - max HTTP redirects (0 - disable redirects); default: 10", PHP_EOL;
	echo "-n\t\t - nesting level for recursive parsing (0 - infinity); default: 0", PHP_EOL;
	echo PHP_EOL;
	echo 'Use ' . CliWrapper::CL_YELLOW . 'report.php' . CliWrapper::CL_DEFAULT . ' for print report to console.', PHP_EOL;
	echo "--domain\t - print report for specified domain (required)", PHP_EOL;
	echo "-p\t\t - max records to print (0 - infinity); default: 0", PHP_EOL;
	echo PHP_EOL;
	echo 'Use ' . CliWrapper::CL_YELLOW . 'help.php' . CliWrapper::CL_DEFAULT . ' for get this help.', PHP_EOL;

} catch (Exception $e) {
	echo $e->getMessage(), PHP_EOL;
	exit();
}
