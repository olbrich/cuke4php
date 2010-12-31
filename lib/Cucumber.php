<?php

/**
 * @package Cuke4Php
 */

/**
 * load all php files in this directory
 */
foreach (glob(dirname(__FILE__) . "/*.php") as $sFilename) {
    require_once $sFilename;
}

?>