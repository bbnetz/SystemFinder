<?php

namespace BbNetz\Runner;

/**
 * class WordpressRunner
 *
 * A specific Runner to find all Wordpress
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class WordpressRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Wordpress';


	/**
	 * function run
	 * Doing a single Run to fetch all Wordpress
	 *
	 * @param string $directory
	 * @param bool   $showExtensions
	 *
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findDirectory($directory, 'wp-content');
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
		$versionFile = file_get_contents($singleDirectory . 'wp-includes/version.php');
		preg_match('/\$wp_version\s*=\s*\'(.*?)\'/', $versionFile, $matches);
		$version = $matches[1];
		unset($versionFile);
		return $version;
	}


	protected function fetchExtensions($singleDirectory) {
		return false;
	}
}

\BbNetz\Run::registerSystem(new WordpressRunner());
