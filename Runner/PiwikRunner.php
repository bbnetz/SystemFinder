<?php

namespace BbNetz\Runner;

/**
 * class PiwikRunner
 *
 * A specific Runner to find all Piwik
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class PiwikRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Piwik';


	/**
	 * function run
	 * Doing a single Run to fetch all Piwik
	 *
	 * @param string $directory
	 * @param boolean   $showExtensions
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findFiles($directory, 'piwik.js');
		foreach($founds as $found) {
			if($this->isShadowEntry($found))
				continue;
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
	 * function isShadowEntry
	 *
	 * @param string $directory
	 * @return boolean
	 */
	protected function isShadowEntry($directory) {
		if(strstr($directory, 'js/'))
			return true;
		if(strstr($directory, 'plugins/CoreHome/angularjs/common/services/'))
			return true;
		return false;
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
		if(!file_exists($singleDirectory . 'core/Version.php'))
			throw new \Exception('Missing File: ' . $singleDirectory . 'core/Version.php');
		$versionFile = file_get_contents($singleDirectory . 'core/Version.php');
		preg_match("/const VERSION = \'(.*?)\'/", $versionFile, $matches);
		$version = $matches[1];
		unset($versionFile);
		return $version;
	}


	/**
	 * function fetchExtensions
	 * Fetch all Extensions for Wordpress
	 *
	 * @param string $singleDirectory
	 * @return void
	 */
	protected function fetchExtensions($singleDirectory) {
		$founds = glob($singleDirectory . 'plugins/*/plugin.json');
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
	 * @throws \Exception if File not found
	 * @return string
	 */
	protected function fetchSingleExtension($singleDirectory) {
		$versionFile = file_get_contents($singleDirectory);
		preg_match("/\"version\"\s*?:\s*?\"(.*?)\"/", $versionFile, $matches);
		$extensionName = explode('/', $singleDirectory);
		if(!isset($matches[1]))
			$matches[1] = '';
		return $extensionName[count($extensionName) - 2] . ' - ' . $matches[1];
	}
}

\BbNetz\Run::registerSystem(new PiwikRunner());
