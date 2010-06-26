<?php

class WireSteps extends CucumberSteps {

    /**
     * Before @wire
     */
    function beforeWire() {
        $this->aGlobals['before'] = 'beforeWire';
    }

    /**
     * Before
     */
    function beforeAll() {
        return array('fail');
    }

    /**
     * Given /^some setup$/
     **/
    public function stepSomeSetup() {
        self::markTestIncomplete("Not Implemented");
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
        self::assertEquals('test',$arg1);
    }

    /**
    * Then /^a step with a "([^"]*)" and the following table\:$/
    **/
    public function stepAStepWithAParameterAndTheFollowingTable($arg1,$table) {
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

}

?>