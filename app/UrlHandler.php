<?php


namespace SSP;


/**
 * Class UrlHandler
 * @package SSP
 */
class UrlHandler
{
	/**
	 * @var array
	 */
	public array $url_components;

	/**
	 * @var string
	 */
	public string $url;

	/**
	 * UrlHandler constructor.
	 * @param string $requested_url
	 * @throws \Exception
	 */
	public function __construct(string $requested_url)
	{
		$this->setUrl($requested_url);
	}

	/**
	 * @param string $requested_url
	 * @return void
	 * @throws \Exception
	 */
	public function setUrl(string $requested_url): void
	{
		$parsed_url = self::parseUrl($requested_url);

		if (!$parsed_url || !filter_var($parsed_url, FILTER_VALIDATE_URL))
			throw new \Exception('Url is not valid!');

		$this->url = $parsed_url;
		$this->url_components = parse_url($parsed_url);
	}

	/**
	 * @param string $requested_url
	 * @return string|null
	 */
	public static function parseUrl(string $requested_url): ?string
	{
		$URL = parse_url($requested_url);
		$parsed_url = null;

		if (empty($URL['scheme']))
			$URL['scheme'] = 'http';

		if (!in_array($URL['scheme'], ['http', 'https']))
			return null;

		if (!empty($URL['host'])) {
			$parsed_url = $URL['scheme'] . '://' . $URL['host'];
			if (!empty($URL['path']))
				$parsed_url .= $URL['path'];
		} elseif (!empty($URL['path']) && preg_match("/^([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/", $URL['path']))
			$parsed_url = $URL['scheme'] . '://' . $URL['path'];
		else
			return null;

		if ($parsed_url && !empty($URL['query']))
			$parsed_url .= '?' . $URL['query'];

		if (!$parsed_url)
			return null;

		return $parsed_url;
	}
}
