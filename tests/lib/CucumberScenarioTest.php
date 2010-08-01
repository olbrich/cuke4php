<?php

require_once dirname(__FILE__) . "/../../lib/Cucumber.php";

class CucumberScenarioTest extends PHPUnit_Framework_TestCase {

    public $oScenario;
    public $aWorld;
    public $aTags;

    public function setup() {

    }

    public function testShouldRunBeforeHooksWithNoTags() {
        $this->aWorld = array('before' => array(
            array(
            'tags' => array(),
            'class' => 'CucumberStep',
            'method' => 'beforeWithNoTags'
        )));
        $this->oScenario = new CucumberScenario($this->aWorld);
        $this->aTags = array('one','two');
        self::assertEquals(array('success'),$this->oScenario->invokeBeforeHooks($this->aTags));
    }
}

?>