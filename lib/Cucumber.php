<?php

/**
 * @package Cuke4Php
 */

/**
 * load all php files except the forking server in this directory
 * Note:  If you require the forking server, it will fail unless pear/Net_Server is installed
 */

require_once dirname(__FILE__) . "/CucumberScenario.php";
require_once dirname(__FILE__) . "/CucumberSteps.php";
require_once dirname(__FILE__) . "/Cuke4Php.php";

?>