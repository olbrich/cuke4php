<?php

class WireSteps {

    /**
     * Before @wire
     */
    function beforeWire() {

    }

    /**
     * Before
     */
    function beforeAll() {

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