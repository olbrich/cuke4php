#!/usr/bin/env php
<?php
include dirname(__FILE__) . "/../lib/Cuke4Php.php";
$aOptions = getopt("p:");
$oServer = new Cuke4Php(realpath($argv[$argc-1]), $aOptions['p']);
$oServer->run();
?>