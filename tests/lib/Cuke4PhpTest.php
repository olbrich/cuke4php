<?php

require_once dirname(__FILE__) . "/../../lib/Cucumber.php";

class Cuke4PhpTest extends PHPUnit_Framework_TestCase {

    public $oCuke4Php;

    function setup() {
        $this->oCuke4Php = new Cuke4Php(dirname(__FILE__) . "/../features");
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

}
?>