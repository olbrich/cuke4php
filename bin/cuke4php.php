#!/usr/bin/env php
<?php
/**
 * main entry point for starting a Cuke4Php wire server
 * @package Cuke4Php
 */

set_include_path(realpath(dirname(__FILE__) . "/../PHPUnit") . ":" . get_include_path());
/**
 * load the Cuke4Php server
 */
require_once dirname(__FILE__) . "/../lib/Cuke4Php.php";
$aOptions = getopt("p:");
$oServer = new Cuke4Php(realpath($argv[$argc-1]), $aOptions['p']);
$oServer->run();
?>