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
	 * @param boolean   $showExtensions
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findDirectory($directory, 'wp-content');
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
		if(!file_exists($singleDirectory . 'wp-includes/version.php'))
			throw new \Exception('Missing File: ' . $singleDirectory . 'wp-includes/version.php');
		$versionFile = file_get_contents($singleDirectory . 'wp-includes/version.php');
		preg_match('/\$wp_version\s*=\s*\'(.*?)\'/', $versionFile, $matches);
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
	 * @throws \Exception if File not found
	 * @return string
	 */
	protected function fetchSingleExtension($singleDirectory) {
		$extensionName = explode('/', $singleDirectory);
		$version = exec('grep -hR "^\s*Version:" ' . $singleDirectory);
		return $extensionName[count($extensionName) - 1] . ' - ' . $version;
	}
}

\BbNetz\Run::registerSystem(new WordpressRunner());
