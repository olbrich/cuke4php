<?php
/**
 * wraps before and after hooks so we can mock them during testing primarily... rspec where are thou?
 */
class CucumberHook {
    static $aMocks = array();

    function __construct() {

    }

    /**
     * @static
     * @param  $sClass
     * @param  $oMock
     * @return void
     *
     * Allows tests to set mock hooks to be used
     */
    static function setMock($sClass, $oMock) {
        self::$aMocks[$sClass] = $oMock;
    }

    /**
     * @static
     * @param  $sClass
     * @param  $aGlobals
     * @return
     *
     * Get an instance of a hook which is either a pre-set mock,
     * or an instance of the appropriate step class with the globals initialized
     */
    static function getInstance($sClass, $aGlobals) {
        if (self::$aMocks[$sClass]) {
            return self::$aMocks[$sClass];
        } else {
            return new $sClass($aGlobals);
        }
    }

    /**
     * @param  $sMethod
     * @return
     */
    function invoke($sMethod) {
        return $this->$sMethod();
    }


}

?>