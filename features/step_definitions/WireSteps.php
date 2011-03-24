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
        $this->beforeWire = true;
    }

    function beforeAll() {
        $this->beforeAll = true;
    }

    /**
     * @wire
     */
    function afterWire() {
        $this->afterWire = true;
    }

    function afterAll() {
        $this->afterAll = true;
    }


    /**
     * Given /^some setup$/
     **/
    public function stepSomeSetup() {
       $this->setup = "true";
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
        $this->exception = $e;
      }
    }

    /**
    * When /^an? "([^"]*)" is thrown with message "([^"]*)"$/
    **/
    public function stepAnExceptionParameterIsThrownWithMessageParameter($sExceptionClass,$sMessage) {
        try {
          throw new $sExceptionClass($sMessage);
        } catch (Exception $e) {
          $this->exception = $e;
        }
    }

    /**
    * Then /^an? "([^"]*)" should be caught$/
    **/
    public function stepAParameterExceptionShouldBeCaught($sExceptionType) {
      self::assertInstanceOf($sExceptionType, $this->exception);
    }

    /**
    * Given /^I store "([^"]*)" into "([^"]*)"$/
    **/
    public function stepIStoreParameterIntoParameter($sValue,$sKey) {
        $this->$sKey = $sValue;
    }


    /**
    * Then /^"([^"]*)" should equal "([^"]*)"$/
    **/
    public function stepParameterShouldEqualParameter($sKey,$sValue) {
        self::assertEquals($sValue, $this->$sKey);
    }

    /**
    * When /^I unset "([^"]*)"$/
    **/
    public function stepIUnsetParameter($sKey) {
        unset($this->$sKey);
    }

    /**
    * Then /^"([^"]*)" (should|should not) be set$/
    **/
    public function stepParameterShouldBeSet($sKey, $sShould) {
      self::assertEquals(($sShould == "should"), isset($this->$sKey));
    }
    
    /**
    * Given /^"([^"]*)" is\:$/
    **/
    public function stepParameterIs($sKey,$aTable) {
      array_shift($aTable); // peel off the table column headings
      $this->$sKey = $aTable;
    }

    /**
    * Then /^"([^"]*)" should equal\:$/
    **/
    public function stepParameterShouldEqual($sKey,$aTable) {
      array_shift($aTable); // peel off the table column headings
      self::assertEquals($aTable, $this->$sKey);
    }
  
    /**
    * Then /^getting "([^"]*)" should raise an? "([^"]*)"$/
    **/
    public function stepGettingParameterShouldRaiseAnParameter($sKey, $sExceptionClass) {
        unset($this->exception);
        try {
          $this->$sKey;
          self::fail("No Exception Caught");
        } catch (Exception $e) {
          $this->exception = $e;
        }
        self::assertInstanceOf($sExceptionClass, $this->exception);
    }
    
    /**
    * Transform /^(\d+)$/
    **/
    public function transformToInteger($sArg) {
      return intval($sArg);
    }

    /**
    * Transform /^(abcd)$/
    **/
    public function transformReverse($sArg) {
      return "dcba";
    }

    /**
    * Transform /^(abcd)$/
    **/
    public function transformCapitalize($sArg) {
      return "ABCD";
    }
    
    /**
    * Transform /^\{(.*)\}$/
    **/
    public function transformSubstituteValues($sArg) {
      return $this->$sArg;
    }
    
    /**
    * Transform /^table:reverse$/
    **/
    public function transformReverseTable($aTable) {
      return array_reverse($aTable);
    }
    

    /**
    * Then /^"([^"]*)" should be a kind of "([^"]*)"$/
    **/
    public function stepParameterShouldBeAKindOfParameter($sKey,$sTypeName) {
        self::assertInternalType($sTypeName, $this->$sKey);
    }
    
}

?>