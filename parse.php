<?php

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH.'/vendor/autoload.php';

try {
	/**
	 * Option list:
	 * url - requested URL (required)
	 * r   - max HTTP redirects (0 - disable redirects)
	 * n   - nesting level for recursive parsing (0 - infinity)
	 */
	$options = \SSP\CliWrapper::getOptions('r:n:', ['url:'], ['url'], ['r' => 10, 'n' => 0]);
} catch (Exception $e) {
	echo $e->getMessage(), PHP_EOL;
	exit();
}
