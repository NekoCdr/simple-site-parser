<?php


namespace SSP\Report;


class ReportPrint
{
	public const INFINITY_PRINT = 0;

	public static function printReport(array $report, int $max_records): bool
	{
		$report = self::reformatReport($report);
		$report_txt = '';
		$records = 0;

		foreach ($report as $document => $content) {
			if ($max_records != self::INFINITY_PRINT && $records >= $max_records)
				break;

			$current_document = 'Page url: ' . $document . PHP_EOL;
			foreach ($content as $type => $data) {
				if ($max_records != self::INFINITY_PRINT && $records >= $max_records)
					break;

				if (empty($data))
					continue;

				foreach ($data as $value) {
					if ($max_records != self::INFINITY_PRINT && $records >= $max_records)
						break;
					$current_document .= $type . ': ' . $value . PHP_EOL;
					$records++;
				}
			}
			$report_txt .= $current_document . PHP_EOL;
		}

		echo $report_txt,
			'Overall documents count: ' . count($report), PHP_EOL,
			'Overall records printed: ' . $records, PHP_EOL;
		return true;
	}

	protected static function reformatReport(array $report): array
	{
		$full_report = [];
		$current_page = '';

		foreach ($report as $line) {
			$record = explode(ReportFS::ELEMENT_DELIMITER, $line);

			if ($record[0] == 'Page url') {
				$current_page = $record[1];
				$full_report[$record[1]] = [];
			} elseif (!empty($current_page)) {
				$full_report[$current_page][$record[0]][] = $record[1];
			}

		}
		return $full_report;
	}
}
