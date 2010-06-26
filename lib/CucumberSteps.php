<?php


class CucumberSteps extends PHPUnit_Framework_Assert {
    protected $aGlobals;

    public function __construct(&$_aGlobals) {
        $this->aGlobals =& $_aGlobals;
    }


}

?>