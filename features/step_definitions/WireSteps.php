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
        return array('failure');
    }

    /**
     * Given /^some setup$/
     **/
    public function stepSomeSetup() {
        return array('success');
    }

    /**
     * When /^I take an action$/
     **/
    public function stepITakeAnAction() {
        return array('success');
    }

    /**
    * Then /^something happens$/
    **/
    public function stepSomethingHappens() {
        return array('success');
    }

    /**
    * Then /^an undefined step with a "([^"]*)"$/
    **/
    public function stepAnUndefinedStepWithAParameter($arg1) {
        print $arg1 . "\n"; 
        return array('success');
    }

    /**
    * Then /^a step with a "([^"]*)" and the following table\:$/
    **/
    public function stepAStepWithAParameterAndTheFollowingTable($arg1,$table) {
        return array('success');
    }

    /**
    * Given /^I shoot the messenger$/
    **/
    public function stepIShootTheMessenger() {
        return array('success');
    }

    /**
     * Then /^It should be dead$/
     **/
    public function stepItShouldBeDead() {
        return array('success');
    }

    /**
     * Given /^I understand the meaning$/
     **/
    public function stepIUnderstandTheMeaning() {
        return array('success');
    }

    /**
     * Then /^It should be clear$/
     **/
    public function stepItShouldBeClear() {
        return array('success');
    }

}

?>