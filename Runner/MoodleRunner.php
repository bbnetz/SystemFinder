<?php

namespace BbNetz\Runner;

/**
 * class MoodleRunner
 *
 * A specific Runner to find all Moodle
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class MoodleRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Moodle';


	/**
	 * function run
	 * Doing a single Run to fetch all Moodle
	 *
	 * @param string $directory
	 * @param boolean   $showExtensions
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findFiles($directory, 'mdeploy.php');
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
		$versionFile = 'version.php';
		if(!file_exists($singleDirectory . $versionFile))
			throw new \Exception('Missing File: ' . $singleDirectory . $versionFile);
		$versionFile = file_get_contents($singleDirectory . $versionFile);
		preg_match('/\$release\s*=\s*\'(.*?)\'/', $versionFile, $matches);
		$version = $matches[1];
		unset($versionFile);
		return $version;
	}

	/**
	 * function fetchExtensions
	 * Fetch all Extensions for Moodle
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

\BbNetz\Run::registerSystem(new MoodleRunner());
