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
    * tags: @wire @wip @pending
    * Given /^some setup$/
    **/
    public function stepSomeSetup() {
        return array("pending");
    }

}

?>