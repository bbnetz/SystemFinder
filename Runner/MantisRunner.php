<?php

namespace BbNetz\Runner;

/**
 * class MantisRunner
 *
 * A specific Runner to find all Mantis
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class MantisRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Mantis';


	/**
	 * function run
	 * Doing a single Run to fetch all Mantis
	 *
	 * @param string $directory
	 * @param boolean   $showExtensions
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findFiles($directory, 'mantisconnect.php');
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
		$versionFile = '../../core/constant_inc.php';
		if(!file_exists($singleDirectory . $versionFile))
			throw new \Exception('Missing File: ' . $singleDirectory . $versionFile);
		$versionFile = file_get_contents($singleDirectory . $versionFile);
		preg_match('/define\s*\(\s*\'MANTIS_VERSION\'\s*,\s*\'(.*?)\'/', $versionFile, $matches);
		$version = $matches[1];
		unset($versionFile);
		return $version;
	}

	/**
	 * function adjustDirectoryPath
	 * If Runner need to digg deep we are able to adjust the displayed path with this function
	 *
	 * @param string $singleDirectory
	 * @return string
	 */
	protected function adjustDirectoryPath($singleDirectory) {
		return str_replace('api/soap/', '', $singleDirectory);
	}


	/**
	 * function fetchExtensions
	 * Fetch all Extensions for Mantis
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

\BbNetz\Run::registerSystem(new MantisRunner());
