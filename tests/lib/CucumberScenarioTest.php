<?php
/**
 * @package Cuke4Php
 */

/**
 * load Cucumber php framework
 */
require_once dirname(__FILE__) . "/../../lib/Cucumber.php";

/**
 * class TestException
 * this class is used during the tests to mock exceptions.
 * @package Cuke4Php
 */
class TestException extends Exception {
    public function __toString() {
        return "TestException";
    }
}

require_once(dirname(__FILE__) . "/../../features/step_definitions/TestSteps.php");

/**
 * class CucumberScenarioTest
 * @package Cuke4Php
 */
class CucumberScenarioTest extends PHPUnit_Framework_TestCase {

    public $oScenario;
    public $aWorld;
    public $aTags;
    public $oMockHook;

    public function setup() {
        CucumberSteps::clearMocks();
        $this->aWorld = array(
            'before' => array(
                array(
                    'tags' => array('one'),
                    'class' => 'TestSteps',
                    'method' => 'beforeWithOneTag'
                     ),
                array(
                    'tags' => array(),
                    'class' => 'TestSteps',
                    'method' => 'beforeWithNoTags'
                )
            ),
            'after' => array(
                array(
                    'tags' => array('one'),
                    'class' => 'TestSteps',
                    'method' => 'afterWithOneTag'
                     ),
                array(
                    'tags' => array(),
                    'class' => 'TestSteps',
                    'method' => 'afterWithNoTags'
                )
            ),
            'steps' => array(
                0 => array(
                    'class' => 'TestSteps',
                    'method' => 'stepSuccessful'
                    ),
                1 => array(
                    'class' => 'TestSteps',
                    'method' => 'stepIncomplete'
                    ),
                2 => array(
                    'class' => 'TestSteps',
                    'method' => 'stepSkipped'
                    ),
                3 => array(
                    'class' => 'TestSteps',
                    'method' => 'stepPending'
                    ),
                4 => array(
                    'class' => 'TestSteps',
                    'method' => 'stepFailed'
                    ),
                5 => array(
                    'class' => 'TestSteps',
                    'method' => 'stepException'
                    ),
                6 => array(
                    'class' => 'TestSteps',
                    'method' => 'stepNotEqual'
                )

            )

        );
        $this->aTags = array('one','two');
        $this->oScenario = new CucumberScenario($this->aWorld);
        $this->oMockHook = $this->getMock('TestSteps', array(
            'beforeWithOneTag',
            'beforeWithNoTags',
            'afterWithOneTag',
            'afterWithNoTags'), array(array()));
    }

    public function testShouldRunBeforeHooksWithAMatchingTag() {
        $this->oMockHook->expects(self::once())->method('beforeWithOneTag');
        $this->oMockHook->expects(self::once())->method('beforeWithNoTags');
        CucumberSteps::setMock('TestSteps', $this->oMockHook);
        self::assertEquals(array('success'),$this->oScenario->invokeBeforeHooks($this->aTags));
    }

    public function testShouldRunBeforeHooksWithNoTags() {
        $this->oMockHook->expects(self::once())->method('beforeWithNoTags');
        CucumberSteps::setMock('TestSteps', $this->oMockHook);
        self::assertEquals(array('success'),$this->oScenario->invokeBeforeHooks(array()));
    }


    public function testShouldRunAfterHooksWithAMatchingTag() {
        $this->oMockHook->expects(self::once())->method('afterWithOneTag');
        $this->oMockHook->expects(self::once())->method('afterWithNoTags');
        CucumberSteps::setMock('TestSteps', $this->oMockHook);
        self::assertEquals(array('success'),$this->oScenario->invokeAfterHooks($this->aTags));
    }

    public function testShouldRunAfterHooksWithNoTags() {
        $this->oMockHook->expects(self::once())->method('afterWithNoTags');
        CucumberSteps::setMock('TestSteps', $this->oMockHook);
        self::assertEquals(array('success'),$this->oScenario->invokeAfterHooks(array()));
    }

    public function testInvokeShouldReturnSuccess() {
        self::assertEquals(array('success'),$this->oScenario->invoke(0,array()));
    }

    public function testInvokeShouldReturnPendingWhenIncomplete() {
        self::assertEquals(array('pending','incomplete'),$this->oScenario->invoke(1,array()));
    }

    public function testInvokeShouldReturnPendingWhenSkipped() {
        self::assertEquals(array('pending','skipped'),$this->oScenario->invoke(2,array()));
    }

    public function testInvokeShouldReturnPendingWhenPending() {
        self::assertEquals(array('pending','pending'),$this->oScenario->invoke(3,array()));
    }

    public function testInvokeShouldFailWhenAssertionNotMet() {
        self::assertEquals(array('fail',array('message' => 'Failed asserting that <boolean:false> is equal to <boolean:true>.')), $this->oScenario->invoke(4,array()));
    }

    public function testInvokeShouldFailWhenExceptionThrown() {
      $aResult = $this->oScenario->invoke(5,array());
      self::assertEquals('fail',$aResult[0]);
      self::assertEquals('Exception', $aResult[1]['exception']);
      self::assertEquals('test exception /Users/kolbrich/local_src/cuke4php/features/step_definitions/TestSteps.php:48', $aResult[1]['message']);
    }

    public function testInvokeShouldSucceedWithParameters() {
        self::assertEquals(array('success'), $this->oScenario->invoke(6,array('one','two')));
    }

}

?>