<?php

namespace BbNetz\Runner;

/**
 * class TYPO3Runner
 * 
 * A specific Runner to find all TYPO3 and to find all
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class TYPO3Runner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'TYPO3';

	/**
	 * function run
	 * Doing a single Run to fetch all TYPO3s
	 *
	 * @param string $directory
	 * @return void
	 */
	public function run($directory) {
		$founds = $this->findDirectory($directory, 'typo3conf');
		foreach($founds as $found) {
			$this->fetchSingle($found);
		}
	}

	/**
	 * function fetchVersion
	 * Getting Systems Directory to import 
	 * 
	 * @param string $singleDirectory
	 * @return string
	 */
	protected function fetchVersion($singleDirectory) {
		return $this->getSingleVersion(str_replace('typo3conf', '', $singleDirectory));
	}

	/**
	 * function getSingleVersion
	 * Checks for each TYPO3 Installation the current version number
	 *
	 * @param string $found
	 * @link https://github.com/bbnetz/TYPO3Updater/blob/master/update.php
	 * @return string Version Number of current Path
	 */
	protected function getSingleVersion($found) {
		if(file_exists($found . '/t3lib/config_default.php')) {
			$content = file_get_contents($found . '/t3lib/config_default.php');
		}elseif(file_exists($found . '/typo3/sysext/core/Classes/Core/SystemEnvironmentBuilder.php')){
			$content = file_get_contents($found . '/typo3/sysext/core/Classes/Core/SystemEnvironmentBuilder.php');
		}else{
			throw new Exception('Version not found for ' . $found);
		}
		if(preg_match("/TYPO_VERSION\s*=\s*'(.*)';/", $content, $match) != 1) {
			if(preg_match("/define\('TYPO3_version', (.*)\)/", $content, $match) != 1)
				throw new Exception('Version not found for ' . $found);
		}
		$version = trim($match[1]);
		$version = str_replace("'", '', $version);

		return $version;
	}
}

\BbNetz\Run::registerSystem(new TYPO3Runner());