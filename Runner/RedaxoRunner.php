<?php

namespace BbNetz\Runner;

/**
 * class RedaxoRunner
 *
 * A specific Runner to find all Redaxo
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class RedaxoRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Redaxo';


	/**
	 * function run
	 * Doing a single Run to fetch all Redaxo
	 *
	 * @param string $directory
	 * @param boolean   $showExtensions
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findFiles($directory, 'class.ooredaxo.inc.php');
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
		$singleDirectory = $this->adjustDirectoryPath($singleDirectory);
		$versionFile = 'include/master.inc.php';
		if(!file_exists($singleDirectory . $versionFile))
			throw new \Exception('Missing File: ' . $singleDirectory . $versionFile);
		$versionFile = file_get_contents($singleDirectory . $versionFile);
		preg_match('/\$REX\[\'DB\'\]\[\'1\'\]\[\'NAME\'\]\s*=\s+"(.*?)";/', $versionFile, $matches);
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
		return str_replace('include/classes/', '', $singleDirectory);
	}


	/**
	 * function fetchExtensions
	 * Fetch all Extensions for Redaxo
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

\BbNetz\Run::registerSystem(new RedaxoRunner());
