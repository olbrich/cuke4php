<?php

class WireSteps extends CucumberSteps {

    /**
     * Before @wire
     */
    function beforeWire() {
        print "->beforeWire\n";
        $this->aGlobals['before'] = 'beforeWire';
    }

    /**
     * Before
     */
    function beforeAll() {
        print "->beforeAll\n";
        return array('failure');
    }

    /**
     * Given /^some setup$/
     **/
    public function stepSomeSetup() {
        return array("pending");
    }

    /**
     * When /^I take an action$/
     **/
    public function stepITakeAnAction() {
        return array('pending');
    }


}

?>