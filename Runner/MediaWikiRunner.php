<?php

namespace BbNetz\Runner;

/**
 * class MediaWikiRunner
 *
 * A specific Runner to find all MediaWikis
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class MediaWikiRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'MediaWiki';

	/**
	 * function run
	 * Doing a single Run to fetch all MediaWikis
	 *
	 * @param string $directory
	 * @return void
	 */
	public function run($directory) {
		$founds = $this->findDirectory($directory, 'mw-config');
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
		$versionFile = file_get_contents($singleDirectory . 'includes/DefaultSettings.php');
		preg_match('/\$wgVersion\s*=\s*\'(.*?)\'/', $versionFile, $matches);
		$version = $matches[1];
		unset($versionFile);
		return $version;
	}
}

\BbNetz\Run::registerSystem(new MediaWikiRunner());
