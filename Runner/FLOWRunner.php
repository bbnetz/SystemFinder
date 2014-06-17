<?php

namespace BbNetz\Runner;

/**
 * class FLOWRunner
 * 
 * A specific Runner to find all FLOW
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class FLOWRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'FLOW';

	/**
	 * function run
	 * Doing a single Run to fetch all FLOWs
	 *
	 * @param string $directory
	 * @return void
	 */
	public function run($directory) {
		$founds = $this->findFiles($directory, 'flow.bat');
		foreach($founds as $found) {
			if(stristr($found, 'Packages/Framework/TYPO3.Flow/Resources/Private/Installer/Distribution/Defaults'))
				continue;
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
		$version = '';
		if(file_exists($singleDirectory . 'Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Core/Bootstrap.php')) {
			$versionFile = file_get_contents($singleDirectory . 'Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Core/Bootstrap.php');
			preg_match('/\'FLOW_VERSION_BRANCH\',\s*\'(.*?)\'/', $versionFile, $matches);
			$version = $matches[1];
			unset($versionFile);
		}else{
			$version = 'NO VERSION'; 
		}
		return $version;
	}

}

\BbNetz\Run::registerSystem(new FLOWRunner());