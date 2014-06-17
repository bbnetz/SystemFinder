SystemFinder
============

Script to automatically find all installed systems on your server

What it does
------------

This small script is crawling over your server and trying to find all systems. Each System has its own worker all workers are own PHP Classes to make it pretty easy to add a system you wrote for yourself.

**Currently known systems:**

* TYPO3
* Wordpress - in Work
* MediaWiki - in Work


Usage
---

##### General Usage:

    $ ./run.php [--onlySystems="..."] [--level="..."] [--baseDir="..."]

##### onlySystems

If set not all possible systems will be used to search and displayed, but only those that are mentioned here. This value can be comma separated and contains the identifier of the runners.

##### level

This is how many times /* should be added after baseDir to allow deeper searchings.

##### baseDir

This is where the search should start. At first this is `.`, which means the current dir.

Known Issues / Missing Features
---

* Currently --recursive is missing
* New folder to add unversionized Runner


Special Thanks
---

Stefan Neufeind for this cool idea


How to contribute
-----------------
The TYPO3 Community lives from your contribution!

You wrote a feature? - Start a pull request!

You found a bug? - Write an issue report!

You are in the need of a feature? - Write a feature request!

You have a problem with the usage? - Ask!
