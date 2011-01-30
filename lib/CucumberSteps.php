<?php
/**
 * @package Cuke4Php
 */
 
/**
 * Base class that all cucumber steps should derive from
 *
 * Each step gets a reference to the global variables array provided by the scenario.
 * This allows steps to save and maintain state over the course of a scenario run.
 *
 * This class also inherits PHPUnit assertions, so those can all be used within the context of a
 * step.  Data providers probably don't work, and really should not be used in any case.
 * @package Cuke4Php
 */
class CucumberSteps extends PHPUnit_Framework_Assert {
    static private $aMocks = array();
    protected $aGlobals;

    public function __construct(&$_aGlobals) {
        $this->aGlobals =& $_aGlobals;
    }

    public static function markPending($sMessage = "Not Implemented") {
        self::markTestIncomplete($sMessage);
    }

    public static function getSubclasses() {
        $aClasses = array();
        foreach (get_declared_classes() as $sClassName) {
            if (is_subclass_of($sClassName, 'CucumberSteps') && (stripos($sClassName,"Mock") === false))
                    $aClasses[] = $sClassName;
        }
        return $aClasses;
    }

    /**
     * @param  $sMethod
     * @return mixed
     */
    function invoke($sMethod) {
        return $this->$sMethod();
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

		static function clearMocks() {
			self::$aMocks = array();
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
        if (array_key_exists($sClass, self::$aMocks) && self::$aMocks[$sClass]) {
            return self::$aMocks[$sClass];
        } else {
            return new $sClass($aGlobals);
        }
    }
    
    public function __set($sName, $sValue) {
      $this->aGlobals[$sName] = $sValue;
    }
    
    public function __get($sName) {
      if (array_key_exists($sName, $this->aGlobals)) {
        return $this->aGlobals[$sName];        
      } else {
        trigger_error("Property not defined", E_USER_ERROR);
      }
    }
    
    public function __unset($sName) {
      unset($this->aGlobals[$sName]);
    }
    
    public function __isset($sName) {
      return isset($this->aGlobals[$sName]);
    }
}

?>