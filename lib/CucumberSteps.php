<?php

/**
 * Base class that all cucumber steps should derive from
 *
 * Each step gets a reference to the global variables array provided by the scenario.
 * This allows steps to save and maintain state over the course of a scenario run.@global
 *
 * This class also inherits PHPUnit assertions, so those can all be used within the context of a
 * step.  Data providers probably don't work, and really should not be used in any case.
 */
class CucumberSteps extends PHPUnit_Framework_Assert {
    protected $aGlobals;

    public function __construct(&$_aGlobals) {
        $this->aGlobals =& $_aGlobals;
    }

    public static function markPending($sMessage = "Not Implemented") {
        self::markTestIncomplete($sMessage);
    }

}

?>