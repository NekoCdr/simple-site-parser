<?php


namespace SSP\Report;


/**
 * Class ReportSave
 * @package SSP\Report
 */
class ReportFS
{
	public const PATH_TO_REPORTS = ROOT_PATH.'/reports';
	public const ELEMENT_DELIMITER = ';;;';

	/**
	 * @param array $report
	 * @param string $host_name
	 * @return string|null
	 * @throws \Exception
	 */
	public static function saveReport(array $report, string $host_name): ?string
	{
		if (empty($report))
			throw new \Exception('Report wasn\'t save: it\'s empty!');

		if (!file_exists(self::PATH_TO_REPORTS))
			if (!mkdir(self::PATH_TO_REPORTS, 0755, true))
				throw new \Exception('Report wasn\'t save: failed to create folders!');

		return self::saveCsv($report, $host_name);
	}

	/**
	 * @param array $report
	 * @param string $host_name
	 * @return string|null
	 * @throws \Exception
	 */
	protected static function saveCsv(array $report, string $host_name): ?string
	{
		$report_txt = '';
		foreach ($report as $document => $content) {
			$report_txt .= 'Page url' . self::ELEMENT_DELIMITER . $document . PHP_EOL;

			foreach ($content as $type => $data) {
				if (empty($data))
					continue;

				foreach ($data as $value) {
					$report_txt .= $type . self::ELEMENT_DELIMITER . $value . PHP_EOL;
				}
			}
		}

		if (empty($report_txt))
			throw new \Exception('Report wasn\'t save: it\'s empty!');

		$filename = $host_name.'.csv';
		$filepath = self::PATH_TO_REPORTS.'/'.$filename;

		if (file_exists($filepath))
			echo 'File ' . $filename . ' is exist, it will be overwritten.', PHP_EOL;

		$file = fopen($filepath, 'w+');
		fwrite($file, $report_txt);
		fclose($file);

		return $filepath;
	}
}
