<?php

namespace BbNetz\Runner;

/**
 * class MySQLDumperRunner
 *
 * A specific Runner to find all MySQLDumper
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class MySQLDumperRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'MySQLDumper';


	/**
	 * function run
	 * Doing a single Run to fetch all MySQLDumper
	 *
	 * @param string $directory
	 * @param boolean   $showExtensions
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findDirectory($directory, 'msd_cron');
		foreach($founds as $found) {
			try {
				$this->fetchSingle($found);
				if($showExtensions)
					$this->fetchExtensions($found);
			}catch(\Exception $e){
				$this->formatOutput('Possible Found', $found);
			}
		}
	}

	/**
	 * function fetchVersion
	 * Getting Systems Directory to import 
	 * 
	 * @param string $singleDirectory
	 * @throws Exception if file is missing
	 * @return string
	 */
	protected function fetchVersion($singleDirectory) {
		$version = '';
		$versionFile = 'inc/runtime.php';
		if(!file_exists($singleDirectory . $versionFile))
			throw new \Exception('Missing File: ' . $singleDirectory . $versionFile);
		$versionFile = file_get_contents($singleDirectory . $versionFile);
		preg_match('/define\s*\(\s*\'MSD_VERSION\'\s*,\s*\'(.*?)\'/', $versionFile, $matches);
		$version = $matches[1];
		unset($versionFile);
		return $version;
	}

	/**
	 * function fetchExtensions
	 * Fetch all Extensions for MySQLDumper
	 *
	 * @param string $singleDirectory
	 * @return void
	 */
	protected function fetchExtensions($singleDirectory) {
		return false;
	}

	/**
	 * function fetchSingleExtension
	 *  Gets Version and Name for a Single Extension
	 *
	 * @param string $singleDirectory
	 * @throws \Exception if File not found
	 * @return string
	 */
	protected function fetchSingleExtension($singleDirectory) {
		return '';
	}
}

\BbNetz\Run::registerSystem(new MySQLDumperRunner());
