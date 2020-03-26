<?php


namespace SSP;


/**
 * Class HTMLDocument
 * @package SSP
 */
class HTMLDocument extends \DOMDocument
{
	/**
	 * @var string
	 */
	public string $document_url;

	/**
	 * @var string
	 */
	public string $requested_url;

	/**
	 * @var int
	 */
	public int $redirect_count;

	/**
	 * @var int
	 */
	public int $max_redirects;

	/**
	 * @var int
	 */
	public int $http_code;

	/**
	 * @param string $requested_url
	 * @param int $max_redirects
	 * @return bool
	 */
	public function loadCurlDocument(string $requested_url, int $max_redirects = 0): bool
	{
		$this->requested_url = $requested_url;
		$this->max_redirects = $max_redirects;

		$curl_data = curl_init($this->requested_url);
		$curl_options = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => $this->max_redirects
		];
		curl_setopt_array($curl_data, $curl_options);
		$curl_response = curl_exec($curl_data);
		$curl_get_info = curl_getinfo($curl_data);

		$this->http_code = $curl_get_info['http_code'];
		$this->redirect_count = $curl_get_info['redirect_count'];

		if ($this->http_code == 200) {
			$this->document_url = $curl_get_info['url'];
			return $this->loadHTML($curl_response);
		} else
			return false;
	}

	/**
	 * @param string $tag_name
	 * @param string $attribute_name
	 * @return array
	 */
	public function getAttributesByTagName(string $tag_name, string $attribute_name): array
	{
		/** @var $dom_element \DOMElement */

		$result_list = [];

		$dom_elements = $this->getElementsByTagName($tag_name);
		foreach ($dom_elements as $dom_element) {
			$attribute_value = $dom_element->getAttribute($attribute_name);
			if (!empty($attribute_value))
				$result_list[] = $attribute_value;
		}
		return $result_list;
	}
}
