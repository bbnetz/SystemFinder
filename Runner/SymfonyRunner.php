<?php

namespace BbNetz\Runner;

/**
 * class SymfonyRunner
 *
 * A specific Runner to find all Symfony
 *
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class SymfonyRunner extends AbstractRunner{

	/**
	 * $identifier
	 * @var string $identifier
	 * Used for output and onlySystems of run
	 */
	public static $identifier = 'Symfony';

	/**
	 * function run
	 * Doing a single Run to fetch all Symfonys
	 * 
	 *
	 * @param string $directory
	 * @return void
	 */
	public function run($directory) {
		$founds = $this->findDirectory($directory, 'Symfony');
		foreach($founds as $found)
			if(strstr($found, 'vendor/symfony/symfony/src/'))
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
		if(file_exists($singleDirectory . 'Symfony/Component/HttpKernel/Kernel.php')) {
			$versionFile = file_get_contents($singleDirectory . 'Symfony/Component/HttpKernel/Kernel.php');
			preg_match('/const\s*VERSION\s*=\s*\'(.*?)\';/', $versionFile, $matches);
			$version = $matches[1];
			unset($versionFile);
		}else{
			$version = 'NO VERSION';
		}
		return $version;
	}
}

\BbNetz\Run::registerSystem(new SymfonyRunner());
