<?php

namespace BbNetz\Runner;

/**
 * class PhpMyAdminRunner
 *
 * A specific Runner to find all PhpMyAdmin
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class PhpMyAdminRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'PhpMyAdmin';


	/**
	 * function run
	 * Doing a single Run to fetch all PhpMyAdmin
	 *
	 * @param string $directory
	 * @param bool   $showExtensions
	 *
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findFiles($directory, 'phpmyadmin.css.php');
		foreach($founds as $found)
			$this->fetchSingle($found);
	}

	/**
	 * function fetchVersion
	 * Getting Systems Directory to import 
	 * 
	 * @param string $singleDirectory
	 * @return string
	 */
	protected function fetchVersion($singleDirectory) {
		$version = '';
		$singleDirectory = str_replace('/css/', '/', $singleDirectory);
		if(file_exists($singleDirectory . 'libraries/defines.lib.php')) {
			$versionFile = file_get_contents($singleDirectory . 'libraries/defines.lib.php');
			preg_match('/\'PMA_VERSION\',\s*\'(.*?)\'/', $versionFile, $matches);
			$version = $matches[1];
			unset($versionFile);
		}elseif(file_exists($singleDirectory . 'libraries/Config.class.php')) {
			$versionFile = file_get_contents($singleDirectory . 'libraries/Config.class.php');
			preg_match('/\'PMA_VERSION\',\s*\'(.*?)\'/', $versionFile, $matches);
			$version = $matches[1];
			unset($versionFile);
		}else{
			$version = 'NO VERSION'; 
		}
		return $version;
	}


	protected function fetchExtensions($singleDirectory) {
		return false;
	}
}

\BbNetz\Run::registerSystem(new PhpMyAdminRunner());
