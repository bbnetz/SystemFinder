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
	 * @param boolean $showExtensions
	 * @return void
	 */
	public function run($directory, $showExtensions = false) {
		$founds = $this->findDirectory($directory, 'typo3conf');
		foreach($founds as $found) {
			try {
				$this->fetchSingle($found);
				if ($showExtensions) {
					$this->formatExtensionOutput($this->fetchExtensions($found));
				}
			} catch(\Exception $e) {
				$this->formatOutput('Possible Found', $found);
			}
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
		try {
			return $this->getSingleVersion($singleDirectory);
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}


	/**
	 * function getSingleVersion
	 * Checks for each TYPO3 Installation the current version number
	 *
	 * @param string $found
	 * @throws \Exception
	 * @link https://github.com/bbnetz/TYPO3Updater/blob/master/update.php
	 * @return string Version Number of current Path
	 */
	protected function getSingleVersion($found) {
		if(file_exists($found . '/t3lib/config_default.php')) {
			$content = file_get_contents($found . '/t3lib/config_default.php');
		}elseif(file_exists($found . '/typo3/sysext/core/Classes/Core/SystemEnvironmentBuilder.php')){
			$content = file_get_contents($found . '/typo3/sysext/core/Classes/Core/SystemEnvironmentBuilder.php');
		}else{
			throw new \Exception('Version not found for ' . $found);
		}
		if(preg_match("/TYPO_VERSION\s*=\s*'(.*)';/", $content, $match) != 1) {
			if(preg_match("/define\('TYPO3_version', (.*)\)/", $content, $match) != 1)
				throw new \Exception('Version not found for ' . $found);
		}
		$version = trim($match[1]);
		$version = str_replace("'", '', $version);

		return $version;
	}



	/**
	 * function calcVersion
	 * Renders an $extensionVersionNumber to a compareable version
	 *
	 * @param string $versionNumber
	 * @return int a mathVersion of the version to compare
	 */
	protected function calcVersion($versionNumber) {
		$version = explode('.', $versionNumber);
		if(!isset($version[1]) || !isset($version[2])) return false;
		$number = 0;
		$number += intval($version[0]) * 10000000;
		$number += intval($version[1]) * 1000;
		$number += intval($version[2]);
		return $number;
	}

	/**
	 * function checkMd5
	 * Compares MD5 Versions of all files of one extension if needed
	 * returns at first found
	 *
	 * @param string $ext the path for each extension
	 * @param string $md5 the md5 serializedObject
	 * @return boolean true if extension is changed
	 */
	protected function checkMd5($ext, $md5) {
		if((!$this->warnModified && !$this->ignoreModified) || $md5 === false)
			return false;
		if($this->checkModificationOnlyFoundInTer && !$this->isInTer($ext))
			return false;
		$md5 = unserialize($md5);
		$ext = str_replace('ext_emconf.php', '', $ext);
		foreach($md5 as $file => $hash) {
			if(file_exists($ext . $file) && $hash != substr(md5(file_get_contents($ext . $file)), 0, 4)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * function formatOutput
	 * Echoing Informations about extension and version
	 *
	 * @param array $elements
	 * @return void
	 */
	protected function formatExtensionOutput(array $elements) {
		foreach($elements as $key => $element) {
			echo "\t" . $key . ' ' . $element[1] . PHP_EOL;
		}
	}

	/**
	 * function fetchExtensions
	 * Getting Systems Extensions like plugins, themes, etc
	 *
	 * @param string $singleDirectory
	 * @return mixed
	 * @abstract
	 */
	protected function fetchExtensions($singleDirectory) {
		$extensions = glob($singleDirectory . 'typo3conf/ext/*/ext_emconf.php');
		$return = array();
		foreach($extensions as $extFile) {
			$content = file_get_contents($extFile);
			preg_match('/\'version\'\s*=>\s*\'(.*?)\'/', $content, $found);
			$extensionName = str_replace($singleDirectory . 'typo3conf/ext/', '', str_replace('/ext_emconf.php', '', $extFile));
			$extensionVersion = $this->calcVersion($found[1]);

			if($extensionVersion !== false) {
				$return[$extensionName] = array($extensionVersion, $found[1]);
			}
		}
		return $return;
	}
}

\BbNetz\Run::registerSystem(new TYPO3Runner());