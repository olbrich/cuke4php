<?php
/**
 * @package Cuke4Php
 */
 
/**
 * load Cucumber framework
 */
require_once dirname(__FILE__) . "/../../lib/Cucumber.php";

/**
 * @package Cuke4Php
 */
class Cuke4PhpTest extends PHPUnit_Framework_TestCase {

    public $oCuke4Php;

    function setup() {
        $this->oCuke4Php = new Cuke4Php(dirname(__FILE__) . "/../../features");
    }

    function testSnippetTextReturnsSnippet() {
        $oSnippet = new StdClass;
        $oSnippet->step_name = "this is a step name";
        $sActual = $this->oCuke4Php->snippetText($oSnippet);
        $sExpected = array('success',"
/**
* /^this is a step name$/
**/
public function stepThisIsAStepName() {
    self::markPending();
}");
        self::assertEquals($sExpected, $sActual);
    }

    function testSnippetTextReturnsSnippetWithParameters() {
        $oSnippet = new StdClass;
        $oSnippet->step_name = 'this is a step with parameter "param1"';
        $sActual = $this->oCuke4Php->snippetText($oSnippet);
        $sExpected = array('success','
/**
* /^this is a step with parameter "([^"]*)"$/
**/
public function stepThisIsAStepWithParameterParameter($arg1) {
    self::markPending();
}');
        self::assertEquals($sExpected, $sActual);
    }

    function testSnippetTextReturnsSnippetWithATable() {
        $oSnippet = new StdClass;
        $oSnippet->step_name = 'this is a step with a table:';
        $oSnippet->multiline_arg_class = "Cucumber::Ast::Table";
        $sActual = $this->oCuke4Php->snippetText($oSnippet);
        $sExpected = array('success','
/**
* /^this is a step with a table\:$/
**/
public function stepThisIsAStepWithATable($aTable) {
    self::markPending();
}');
        self::assertEquals($sExpected, $sActual);
    }

    function testSnippetTextReturnsSnippetWithAMultilineString() {
        $oSnippet = new StdClass;
        $oSnippet->step_name = 'this is a step with a pystring:';
        $oSnippet->multiline_arg_class = "Cucumber::Ast::PyString";
        $sActual = $this->oCuke4Php->snippetText($oSnippet);
        $sExpected = array('success','
/**
* /^this is a step with a pystring\:$/
**/
public function stepThisIsAStepWithAPystring($sString) {
    self::markPending();
}');
        self::assertEquals($sExpected, $sActual);
    }

    function testBeginScenarioShouldInvokeBeforeHooks() {
        $oMockScenario = $this->getMock('CucumberScenario', array('invokeBeforeHooks'));
        $oMockScenario->expects(self::once())->method('invokeBeforeHooks');
        CucumberScenario::setInstance($oMockScenario);
        $this->oCuke4Php->beginScenario(array());
    }

    function testEndScenarioShouldInvokeAfterHooks() {
        $oMockScenario = $this->getMock('CucumberScenario', array('invokeAfterHooks'));
        $oMockScenario->expects(self::once())->method('invokeAfterHooks');
        $this->oCuke4Php->setScenario($oMockScenario);
        $this->oCuke4Php->endScenario(array());
    }

    function testStepMatchesShouldReturnEmptySetWhenNoMatches() {
        self::assertEquals(array('success',array()), $this->oCuke4Php->stepMatches("random step"));
    }

    function testStepMatchesShouldReturnMatches() {
        self::assertEquals(array('success',array(
            array('id' => 0, 'args' => array(), 'source' => realpath(dirname(__FILE__) . '/../../features/step_definitions/TestSteps.php') . ":8")            
        )), $this->oCuke4Php->stepMatches("successful"));
    }

    function testStepMatchesShouldReturnMatchesWithParameters() {
        self::assertEquals(array('success',array(
            array('id' => 6, 'args' => array(), 'source' => realpath(dirname(__FILE__) . '/../../features/step_definitions/TestSteps.php') . ":48")
        )), $this->oCuke4Php->stepMatches('"arg1" not equal to "arg2"'));
    }

}
?>