<?php
/**
 * @package Cuke4Php
 */
 
/**
 * load the PHPUnit framework, try to load the new version first
 * then the older one.
 */ 
	@include_once "PHPUnit/Autoload.php";	
	@include_once "PHPUnit/Framework.php";

  function shutdown()
  {
    echo "Cuke4php server gracefully shutdown\n";
  }
  
  register_shutdown_function('shutdown');

?>