#!/usr/bin/env php
<?php
set_include_path(realpath(dirname(__FILE__) . "/../PHPUnit") . ":" . get_include_path());
require_once dirname(__FILE__) . "/../lib/Cuke4Php.php";
$aOptions = getopt("p:");
$oServer = new Cuke4Php(realpath($argv[$argc-1]), $aOptions['p']);
$oServer->run();
?>