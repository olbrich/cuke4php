<?php

require_once dirname(__FILE__) . "/../../lib/Cucumber.php";

class CucumberScenarioTest extends PHPUnit_Framework_TestCase {

    public $oScenario;
    public $aWorld;
    public $aTags;
    public $oMockHook;

    public function setup() {
        CucumberHook::setMock('MockCucumberHook',null);
        $this->aWorld = array(
            'before' => array(
                array(
                    'tags' => array('one'),
                    'class' => 'MockCucumberHook',
                    'method' => 'beforeWithOneTag'
                     ),
                array(
                    'tags' => array(),
                    'class' => 'MockCucumberHook',
                    'method' => 'beforeWithNoTags'
                )
            ),
            'after' => array(
                array(
                    'tags' => array('one'),
                    'class' => 'MockCucumberHook',
                    'method' => 'afterWithOneTag'
                     ),
                array(
                    'tags' => array(),
                    'class' => 'MockCucumberHook',
                    'method' => 'afterWithNoTags'
                )
            )
        );
        $this->aTags = array('one','two');
        $this->oScenario = new CucumberScenario($this->aWorld);
        $this->oMockHook = $this->getMock('CucumberHook', array(
            'beforeWithOneTag',
            'beforeWithNoTags',
            'afterWithOneTag',
            'afterWithNoTags'));
    }

    public function testShouldRunBeforeHooksWithAMatchingTag() {
        $this->oMockHook->expects(self::once())->method('beforeWithOneTag');
        $this->oMockHook->expects(self::once())->method('beforeWithNoTags');
        CucumberHook::setMock('MockCucumberHook', $this->oMockHook);
        self::assertEquals(array('success'),$this->oScenario->invokeBeforeHooks($this->aTags));
    }

    public function testShouldRunBeforeHooksWithNoTags() {
        $this->oMockHook->expects(self::once())->method('beforeWithNoTags');
        CucumberHook::setMock('MockCucumberHook', $this->oMockHook);
        self::assertEquals(array('success'),$this->oScenario->invokeBeforeHooks(array()));
    }


    public function testShouldRunAfterHooksWithAMatchingTag() {
        $this->oMockHook->expects(self::once())->method('afterWithOneTag');
        $this->oMockHook->expects(self::once())->method('afterWithNoTags');
        CucumberHook::setMock('MockCucumberHook', $this->oMockHook);
        self::assertEquals(array('success'),$this->oScenario->invokeAfterHooks($this->aTags));
    }

    public function testShouldRunAfterHooksWithNoTags() {
        $this->oMockHook->expects(self::once())->method('afterWithNoTags');
        CucumberHook::setMock('MockCucumberHook', $this->oMockHook);
        self::assertEquals(array('success'),$this->oScenario->invokeAfterHooks(array()));
    }

}

?>