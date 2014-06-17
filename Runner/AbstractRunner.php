<?php

namespace BbNetz\Runner;

/**
 * class AbstractRunner
 *
 * Basic Runner for All Actions. No real Work possible.
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
abstract class AbstractRunner {

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Abstract';

	/**
	 * function run
	 * Doing a single Run to fetch all Systems of current run
	 *
	 * @param string $directory
	 * @return void
	 */
	public abstract function run($directory);

	/**
	 * function fetchVersion
	 * Getting Systems Directory to import 
	 * 
	 * @param string $singleDirectory
	 * @return string
	 * @abstract
	 */
	protected abstract function fetchVersion($singleDirectory);

	/**
	 * function fetchSingle
	 * After finding entries in run this method should be called for each single found
	 *
	 * @param string $singleDirectory
	 * @return void
	 */
	protected function fetchSingle($singleDirectory) {
		$this->formatOutput($this->fetchVersion($singleDirectory), $singleDirectory);
	}

	/**
	 * function formatOutput
	 * Echoing Informations about path, version and system
	 *
	 * @param string $version
	 * @param string $directory
	 * @return void
	 */
	protected function formatOutput($version, $directory) {
		echo '[' . static::$identifier . '] ' . $version . ' - ' . $directory . PHP_EOL;
	}
}