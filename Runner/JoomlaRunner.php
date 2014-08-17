<?php

namespace BbNetz\Runner;

/**
 * class JoomlaRunner
 *
 * A specific Runner to find all Joomla
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class JoomlaRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Joomla';


	/**
	 * function run
	 * Doing a single Run to fetch all Joomla
	 *
	 * @param string $directory
	 * @param boolean   $showExtensions
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findDirectory($directory, 'administrator');
		foreach($founds as $found) {
			try {
				$this->fetchSingle($found);
				/**
				 * Currently I am not sure how to find ExtensionVersions
				 */
				// if($showExtensions)
				//	$this->fetchExtensions($found);
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
		if(!file_exists($singleDirectory . 'language/en-GB/en-GB.xml'))
			throw new \Exception('Missing File: ' . $singleDirectory . 'language/en-GB/en-GB.xml');
		$versionFile = file_get_contents($singleDirectory . 'language/en-GB/en-GB.xml');
		preg_match('/<version>(.*?)<\/version>/', $versionFile, $matches);
		$version = $matches[1];
		unset($versionFile);
		return $version;
	}


	/**
	 * function fetchExtensions
	 * Fetch all Extensions for Joomla
	 *
	 * @param string $singleDirectory
	 * @deprecated not used for this runner
	 * @return void
	 */
	protected function fetchExtensions($singleDirectory) {
		$founds = glob($singleDirectory . 'wp-content/plugins/*');
		foreach($founds as $found) {
			try{
				echo "\t" . $this->fetchSingleExtension($found) . PHP_EOL;
			}catch(\Exception $e){
				echo $e->getMessage();
				// Do nothing
			}
		}
	}

	/**
	 * function fetchSingleExtension
	 *  Gets Version and Name for a Single Extension
	 *
	 * @param string $singleDirectory
	 * @deprecated not used for this runner
	 * @throws \Exception if File not found
	 * @return string
	 */
	protected function fetchSingleExtension($singleDirectory) {
		$extensionName = explode('/', $singleDirectory);
		$version = exec('grep -hR "^\s*Version:" ' . $singleDirectory);
		return $extensionName[count($extensionName) - 1] . ' - ' . $version;
	}
}

\BbNetz\Run::registerSystem(new JoomlaRunner());
