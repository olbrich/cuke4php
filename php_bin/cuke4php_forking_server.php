#!/usr/bin/env php
<?php
/**
 * main entry point for starting a Cuke4Php wire server
 * @package Cuke4Php
 */

/**
 * load the Cuke4PhpForkingServer server
 */
require_once dirname(__FILE__) . "/../lib/Cuke4PhpForkingServer.php";

$aOptions = getopt("p:");
if (array_key_exists('p',$aOptions)) {
    $iPort = $aOptions['p'];
} else {
    $iPort = 16816;
}

$oServer = &Net_Server::create('fork','0.0.0.0', $iPort);
$oServerHandler = &new Cuke4PhpForkingServer(realpath($argv[$argc-1]), $iPort);
$oServer->setCallbackObject($oServerHandler);
$oServer->start();
?>
