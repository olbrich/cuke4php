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
       if (is_null($aTags)) {
         $aTags = array();
       }
       foreach ($this->aWorld['before'] as $aBeforeHook) {
         if (array_key_exists('tags', $aBeforeHook) || $aBeforeHook['method'] == 'beforeAll') {
           if (count($aBeforeHook['tags']) == 0 || count(array_intersect($aTags, $aBeforeHook['tags'])) > 0) {
             $oStep = CucumberSteps::getInstance($aBeforeHook['class'], $this->aGlobals);
             syslog(LOG_DEBUG,"Invoking Before Hook \"{$aBeforeHook['method']}\"");
             $oResult = $oStep->invoke($aBeforeHook['method']);
             if ($oResult === false) {
               return array('failure');
             }
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
       if (is_null($aTags)) {
         $aTags = array();
       }
       foreach ($this->aWorld['after'] as $aAfterHook) {
         if (array_key_exists('tags', $aAfterHook) || $aAfterHook['method'] == 'afterAll') {
           if (count($aAfterHook['tags']) == 0 || count(array_intersect($aTags, $aAfterHook['tags'])) > 0) {
             $oStep = CucumberSteps::getInstance($aAfterHook['class'], $this->aGlobals);
             syslog(LOG_DEBUG,"Invoking After Hook \"{$aAfterHook['method']}\"");
             $oResult = $oStep->invoke($aAfterHook['method']);
             if ($oResult === false) {
               return array('failure');
             }
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
        foreach ($aArgs as &$sArg) {
          $sArgTest = $sArg;
          if (is_array($sArg)) {
            $sArgTest = "table:" . implode(",",$sArgTest[0]);
          }
          foreach (array_reverse($this->aWorld['transform'],true) as $aTransform) {
            $aMatches = array();
            if (preg_match_all($aTransform['regexp'], $sArgTest, $aMatches, PREG_OFFSET_CAPTURE)) {
              $oTransform = new $aTransform['class']($this->aGlobals);
              if (is_array($sArg)) {
                $sArg = call_user_func_array(array($oTransform, $aTransform['method']),array($sArg));
              } else {
                $sArg = call_user_func_array(array($oTransform, $aTransform['method']),$aMatches[1][0]);                
              }
              break;
            }
          }
          
        }
        try {
            syslog(LOG_DEBUG,"Invoking Step \"{$aStep['method']}\"");
            call_user_func_array(array($oStep, $aStep['method']),$aArgs);
        } catch (PHPUnit_Framework_IncompleteTestError $e) {
            syslog(LOG_DEBUG,"Step Pending");
            return array('pending',$e->getMessage());
        } catch (PHPUnit_Framework_SkippedTestError $e) {
            syslog(LOG_DEBUG,"Step Pending");
            return array('pending',$e->getMessage());
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            syslog(LOG_DEBUG,"Step Failed due to unmet expectation: " . $e->getMessage());
            return array('fail', array('message' => $e->getMessage()));
        } catch (Exception $e) {
            syslog(LOG_DEBUG,"Step failed due to ". get_class($e) ." exception :" . $e->getMessage());
            return array('fail', array('message' => $e->getMessage() . " " . $e->getFile() . ":" . $e->getLine(), 'exception' => get_class($e), 'backtrace' => $e->getTraceAsString()));
        }
        return array('success');
    }

}
?>