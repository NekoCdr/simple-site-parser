<?php


namespace SSP;


/**
 * Class CliWrapper
 * @package SSP
 */
class CliWrapper
{
	/**
	 * @brief Console text colors.
	 */
	public const CL_RED     = "\033[31m";
	public const CL_GREEN	= "\033[32m";
	public const CL_YELLOW	= "\033[33m";
	public const CL_WHITE	= "\033[37m";
	public const CL_DEFAULT	= "\033[39m";

	/**
	 * @param string $options
	 * @param array $longopts
	 * @param array $required
	 * @param array $defaults
	 * @return array
	 * @throws \Exception
	 */
	public static function getOptions(string $options, array $longopts = [], array $required = [], array $defaults = []): array
	{
		$argv = $GLOBALS['argv'];
		$optind = null;

		$set_options = getopt($options, $longopts, $optind);

		if (array_slice($argv, $optind))
			throw new \Exception('Syntax error. Invalid argument: ' . self::CL_RED . $argv[$optind] . self::CL_DEFAULT);

		foreach ($required as $option) {
			if (!isset($set_options[$option]))
				throw new \Exception('Application error! ' . self::CL_RED . $option . self::CL_DEFAULT . ' option must be provided!');
		}

		foreach ($defaults as $option => $value) {
			if (!isset($set_options[$option]))
				$set_options[$option] = $value;
		}

		return $set_options;
	}
}
