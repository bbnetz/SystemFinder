<?php
namespace BbNetz;

/**
 * Class Run
 * 
 * Doing the basic collection work and delegating
 * CopyRight 2014 Bastian Bringenberg <mail@bastian-bringenberg.de>
 * This work is under GPLv3 license.
 *
 * @todo recursive without blob
 * @author Bastian Bringenberg <mail@bastian-bringenberg.de>
 */
class Run{

	/**
	 * systems
	 * @var array $systems
	 * 
	 */
	protected static $systems = array();

	/**
	 * baseDir
	 * @var string $baseDir
	 * Variable to start the search
	 */
	protected $baseDir = '.';

	/**
	 * onlySystems
	 * @var array $onlySystems
	 * If variable is not empty registerSystem is filtered against this
	 */
	protected static $onlySystems = array();

	/**
	 *
	 * @var bool
	 */
	protected $showExtensions = false;

	/**
	 * function registerSystem
	 * Registers Runner but checks if runner is in $onlySytems
	 * if $onlySystems is notEmpty.
	 *
	 * @param \BbNetz\Runner\AbstractRunner $runner
	 * @return void
	 */
	public static function registerSystem(\BbNetz\Runner\AbstractRunner $runner) {
		if(!empty(\BbNetz\Run::$onlySystems)) {
			if(in_array($runner::$identifier, \BbNetz\Run::$onlySystems))
				\BbNetz\Run::$systems[] = $runner;
		}else{
			\BbNetz\Run::$systems[] = $runner;
		}
		
	}

	/**
	 * function setOnlySystems
	 * Importing $onlySystems variable trimmed and exploded
	 *
	 * @param string $onlySystems
	 * @return void
	 */
	public static function setOnlySystems($onlySystems) {
		$tmp = explode(',', $onlySystems);
		foreach($tmp as $single) {
			\BbNetz\Run::$onlySystems[] = trim($single);
		}
	}

	/**
	 * function setBaseDir
	 * Sets BaseDir and adds /* if level is set
	 *
	 * @param string $baseDir
	 * @return void
	 */
	public function setBaseDir($baseDir) {
		$this->baseDir = $baseDir;
	}

	public function setShowExtensions($showExtensions) {
		$this->showExtensions = $showExtensions;
	}

	/**
	 * function __construct
	 * Constructing all required Settings and Importing Runners
	 *
	 * @return \BbNetz\Run
	 */
	public function __construct() {
		$ops = getopt('',
			array(
				'',
				'baseDir::',
				'onlySystems::',
				'showExtensions::',
			)
		);

		if(isset($ops['onlySystems']))
			\BbNetz\Run::setOnlySystems($ops['onlySystems']);

		if(isset($ops['baseDir']))
			$this->setBaseDir($ops['baseDir']);

		if(isset($ops['showExtensions']))
			$this->setShowExtensions(true);

		foreach (glob("Runner/*Runner.php") as $filename)
			require_once $filename;

		foreach (glob("MyRunner/*Runner.php") as $filename)
			require_once $filename;
	}

	/**
	 * function run
	 * Calling all Systems -> run Method
	 *
	 * @return void
	 */
	public function run() {
		foreach(\BbNetz\Run::$systems as $system)
			$system->run($this->baseDir, $this->showExtensions);
	}

}

$runner = new \BbNetz\Run();
$runner->run();