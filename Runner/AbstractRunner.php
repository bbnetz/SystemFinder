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
	public abstract function run($directory, $showExtensions = false);

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
	 * function fetchExtensions
	 * Getting Systems Extensions like plugins, themes, etc
	 *
	 * @param $singleDirectory
	 * @return mixed
	 * @abstract
	 */
	protected abstract function fetchExtensions($singleDirectory);

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

	/**
	 * function findDirectory
	 * Working recursive through all directories under $directoryBase for directory named $directory
	 *
	 * @param string $directoryBase
	 * @param string $directory
	 * @return array
	 */
	protected function findDirectory($directoryBase, $directory) {
		$return = array();
		exec("find " . $directoryBase . " -type d -name '" . $directory . "' 2> /dev/null", $tmp);
		foreach($tmp as $r)
			$return[] = str_replace($directory, '', $r);
		return $return;
	}

	/**
	 * function findFiles
	 * Working recursive through all directories under $directoryBase for file named $file
	 *
	 * @param string $directoryBase
	 * @param string $file
	 * @return array
	 */
	protected function findFiles($directoryBase, $file) {
		$return = array();
		exec("find " . $directoryBase . " -type f -name '" . $file . "' 2> /dev/null", $tmp);
		foreach($tmp as $r)
			$return[] = str_replace($file, '', $r);
		return $return;
	}
}