<?php
class CucumberScenario {

    // provide a place we can store data
    public $aGlobals = array();

    private $aStepDefinitions = array();
    private $aBeforeHooks = array();
    private $aAfterHooks = array();
    private $aTags = array();

    function __construct($_aTags = array()) {
        $this->aTags = $_aTags;
    }

    function invokeBeforeHooks() {

    }

    function invokeAfterHooks() {

    }

    function invoke($aArgs) {

    }

    

}
?>