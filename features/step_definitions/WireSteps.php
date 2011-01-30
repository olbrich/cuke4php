<?php
/**
 * @package Cuke4Php
 */
/**
 * @package Cuke4Php
 */ 
class WireSteps extends CucumberSteps {

    /**
     * @wire
     */
    function beforeWire() {
        $this->aGlobals['before'] = 'beforeWire';
    }

    function beforeAll() {

    }

    /**
     * Given /^some setup$/
     **/
    public function stepSomeSetup() {
        
    }

    /**
     * When /^I take an action$/
     **/
    public function stepITakeAnAction() {
    }

    /**
     * Then /^something happens$/
     **/
    public function stepSomethingHappens() {
    }

    /**
     * Then /^an undefined step with a "([^"]*)"$/
     **/
    public function stepAnUndefinedStepWithAParameter($arg1) {
        self::assertEquals('param', $arg1);
    }

    /**
     * Then /^a step with a "([^"]*)" and the following table\:$/
     **/
    public function stepAStepWithAParameterAndTheFollowingTable($arg1, $table) {
    }

    /**
     * Given /^I shoot the messenger$/
     **/
    public function stepIShootTheMessenger() {
    }

    /**
     * Then /^It should be dead$/
     **/
    public function stepItShouldBeDead() {
    }

    /**
     * Given /^I understand the meaning$/
     **/
    public function stepIUnderstandTheMeaning() {
    }

    /**
     * Then /^It should be clear$/
     **/
    public function stepItShouldBeClear() {
    }

    /**
     * Then /^a step with a multiline string\:$/
     **/
    public function stepAStepWithAMultilineString($sString) {

    }

    /**
    * When /^an error "([^"]*)" with message "([^"]*)" occurs$/
    **/
    public function stepAnErrorParameterWithMessageParameterOccurs($sType,$sMessage) {
      try {
        trigger_error($sMessage, constant($sType));        
      } catch (Exception $e) {
        $this->aGlobals['exception'] = $e;
      }
    }

    /**
    * When /^an? "([^"]*)" is thrown with message "([^"]*)"$/
    **/
    public function stepAnExceptionParameterIsThrownWithMessageParameter($sExceptionClass,$sMessage) {
        try {
          throw new $sExceptionClass($sMessage);
        } catch (Exception $e) {
          $this->aGlobals['exception'] = $e;
        }
    }

    /**
    * Then /^an? "([^"]*)" should be caught$/
    **/
    public function stepAParameterExceptionShouldBeCaught($sExceptionType) {
      self::assertInstanceOf($sExceptionType, $this->aGlobals['exception']);
    }

}

?>