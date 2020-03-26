<?php

use SSP\CliWrapper;
use SSP\HTMLDocument;

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH.'/vendor/autoload.php';

try {
	/**
	 * Option list:
	 * url - requested URL (required)
	 * r   - max HTTP redirects (0 - disable redirects)
	 * n   - nesting level for recursive parsing (0 - infinity)
	 */
	$options = CliWrapper::getOptions('r:n:', ['url:'], ['url'], ['r' => 10, 'n' => 0]);

	$doc = new HTMLDocument();
	if (!$doc->loadCurlDocument($options['url'], $options['r'])) {
		if ($doc->http_code == 308 && $doc->redirect_count == $doc->max_redirects)
			throw new Exception("Document wasn't load. Too many redirects.");
		else
			throw new Exception("Document wasn't load. HTTP code: {$doc->http_code}");
	}
} catch (Exception $e) {
	echo $e->getMessage(), PHP_EOL;
	exit();
}
