#!/usr/bin/env php
<?php
var_dump(set_include_path(realpath(dirname(__FILE__) . "/../PHPUnit") . ":/usr/share/php"));
require_once dirname(__FILE__) . "/../lib/Cuke4Php.php";
$aOptions = getopt("p:");
$oServer = new Cuke4Php(realpath($argv[$argc-1]), $aOptions['p']);
$oServer->run();
?>