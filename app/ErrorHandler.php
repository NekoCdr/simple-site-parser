<?php


namespace SSP;


class ErrorHandler
{
	public const PATH_TO_LOGS = ROOT_PATH.'/logs';

	public static function saveLibxmlLogs(array $libxml_log): string
	{
		$answer = '';
		$log_txt = '';
		$log_time = date('Y.m.d H:i', time());

		/** @var \LibXMLError $error */
		foreach ($libxml_log as $error) {
			$log_txt .= "[$log_time] ";

			switch ($error->level) {
				case LIBXML_ERR_WARNING:
					$log_txt .= "Warning $error->code: ";
					break;
				case LIBXML_ERR_ERROR:
					$log_txt .= "Error $error->code: ";
					break;
				case LIBXML_ERR_FATAL:
					$log_txt .= "Fatal Error $error->code: ";
					break;
			}
			$log_txt .= trim($error->message) . ', line: ' . $error->line . PHP_EOL;
		}

		$answer .= 'Libxml errors: ' . count($libxml_log) . '. ';

		if (!file_exists(self::PATH_TO_LOGS))
			if (!mkdir(self::PATH_TO_LOGS, 0755, true))
				return $answer . 'Log wasn\'t save: failed to create folders!' . PHP_EOL;

		$filename = 'libxml_errors.log';
		$filepath = self::PATH_TO_LOGS.'/'.$filename;

		$file = fopen($filepath, 'a+');
		fwrite($file, $log_txt);
		fclose($file);

		$answer .= 'Log saved to: ' . $filepath . PHP_EOL;
		return $answer;
	}
}