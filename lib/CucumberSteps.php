<?php

class CucumberSteps {
    protected $aGlobals;

    public function __construct(&$_aGlobals) {
        $this->aGlobals =& $_aGlobals;
    }


}

?>