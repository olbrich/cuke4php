<?php

/**
 * This class allows multiple concurrent connections to the same Cuke4php port.  Each connection forks a process that 
 * loads the same feature files and then runs the step definitions.  This is useful for cases where the Cuke4php server
 * is deployed to a remote machine and multiple automation scripts are simultaneously connecting to the server.
 *
 * Uses the PEAR Net_Server package
 **/

require_once "Cuke4Php.php";
require_once "Net/Server.php";
require_once "Net/Server/Handler.php";

class Cuke4PhpForkingServer extends Net_Server_Handler
{
	private $cuke4php;

	function __construct($_sFeaturePath, $_iPort) {
		$this->cuke4php = new Cuke4Php($_sFeaturePath, $_iPort);
	}

	function onReceiveData($iClientId = 0, $data = "") {
		$output = json_encode($this->cuke4php->process($data)) . "\n";
		$this->_server->sendData($iClientId, $output);
	}

	function onClose($iClientId) {
		// Do nothing
	}
}


?>