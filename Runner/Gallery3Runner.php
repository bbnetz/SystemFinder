<?php

namespace BbNetz\Runner;

/**
 * class Gallery3Runner
 * 
 * A specific Runner to find all Gallery3
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class Gallery3Runner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Gallery3';

	/**
	 * function run
	 * Doing a single Run to fetch all Gallery3s
	 *
	 * @param string $directory
	 * @return void
	 */
	public function run($directory) {
		$founds = $this->findFiles($directory, 'gallery_theme.php');
		foreach($founds as $found) {
			$this->fetchSingle(str_replace('modules/gallery/helpers/', '', $found));
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
		$version = '';
		if(file_exists($singleDirectory . 'modules/gallery/helpers/gallery.php')) {
			$versionFile = file_get_contents($singleDirectory . 'modules/gallery/helpers/gallery.php');
			preg_match('/const\s*VERSION\s*=\s*"(.*?)"/', $versionFile, $matches);
			$version = $matches[1];
			unset($versionFile);
		}else{
			$version = 'NO VERSION'; 
		}
		return $version;
	}

}

\BbNetz\Run::registerSystem(new Gallery3Runner());