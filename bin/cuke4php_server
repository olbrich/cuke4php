#!/usr/bin/env php
<?php
/**
 * main entry point for starting a Cuke4Php wire server
 * @package Cuke4Php
 */

/**
 * load the Cuke4Php server
 */
require_once dirname(__FILE__) . "/../lib/Cuke4Php.php";
$aOptions = getopt("p:");
if (array_key_exists('p',$aOptions)) {
    $iPort = $aOptions['p'];
} else {
    $iPort = null;
}
$oServer = new Cuke4Php(realpath($argv[$argc-1]), $iPort);
$oServer->run();
?>
