<?php
/**
 * @package Cuke4Php
 */

/**
 * load dependencies
 */
require_once "CucumberSteps.php";

/**
 * class CucumberScenario
 * Defines a Cucumber Scenario
 * @package Cuke4Php
 */
class CucumberScenario {

    // provide a place we can store data
    public $aGlobals = array();

    // the world holds the definitions for the before, after, and step definitions
    private $aWorld;

    static private $oMock;

    /**
     * @param array $_aWorld
     * @return void
     */
    function __construct($_aWorld = array()) {
        $this->aWorld = $_aWorld;
    }

    /**
     * @static
     * @param  $aWorld
     * @return CucumberScenario
     */
    static function getInstance($aWorld) {
        if (self::$oMock) {
            return self::$oMock;
        } else {
            return new CucumberScenario($aWorld);
        }
    }

    /**
     * @static
     * @param  $oMock
     * @return void
     */
    static function setInstance($oMock) {
        self::$oMock = $oMock;
    }

    /**
     * @param  $aTags
     * @return array
     * invokes all the before hooks defined that either have no tags or tags corresponding to this scenario's tags
     */
     function invokeBeforeHooks($aTags) {
       foreach ($this->aWorld['before'] as $aBeforeHook) {
         if (array_key_exists('tags', $aBeforeHook))
         if (count($aBeforeHook['tags']) == 0 || count(array_intersect($aTags, $aBeforeHook['tags'])) > 0) {
           $oStep = CucumberSteps::getInstance($aBeforeHook['class'], $this->aGlobals);
           $oResult = $oStep->invoke($aBeforeHook['method']);
           if ($oResult === false) {
             return array('failure');
           }
         }
       }
       return array('success');
     }

    /**
     * @param  $aTags
     * @return array
     * invoke all after hooks defined that either have no tags, or tags that match the tags of the current scenario
     */
     function invokeAfterHooks($aTags) {
       foreach ($this->aWorld['after'] as $aAfterHook) {
         if (array_key_exists('tags', $aAfterHook))
         if (count($aAfterHook['tags']) == 0 || count(array_intersect($aTags, $aAfterHook['tags'])) > 0) {
           $oStep = CucumberSteps::getInstance($aAfterHook['class'], $this->aGlobals);
           $oResult = $oStep->invoke($aAfterHook['method']);
           if ($oResult === false) {
             return array('failure');
           }
         }
       }
       return array('success');
     }

    /**
     * @param  $iStepId
     * @param  $aArgs
     * @return mixed
     *
     * Invokes a step.  Steps can use PHPUnit assertions and will
     * mark themselves as pending if the self::markTestIncomplete() or self:markTestSkipped()
     * functions are called.  Failed expectations are returned as messages while all other
     * Exceptions are reported back as exceptions.
     */
    function invoke($iStepId, $aArgs) {
        $aStep = $this->aWorld['steps'][$iStepId];
        $oStep = new $aStep['class']($this->aGlobals);
        try {
            call_user_func_array(array($oStep, $aStep['method']),$aArgs);
        } catch (PHPUnit_Framework_IncompleteTestError $e) {
            return array('pending',$e->getMessage());
        } catch (PHPUnit_Framework_SkippedTestError $e) {
            return array('pending',$e->getMessage());
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            return array('fail', array('message' => $e->getMessage()));
        } catch (Exception $e) {
            return array('fail', array('message' => $e->getMessage() . " " . $e->getFile() . ":" . $e->getLine(), 'exception' => get_class($e)));            
        }
        return array('success');
    }

}
?>