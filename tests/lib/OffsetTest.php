<?php

require_once dirname(__FILE__) . "/../../lib/Cucumber.php";

class OffsetTest extends \PHPUnit_Framework_TestCase {

    const FEATURE_FILE = '1.feature';
    const STARTLINE = 1;

    private $cuke;

    public function setUp() {
        $this->cuke = new Cuke4Php(dirname(__FILE__) . "/../../features");
    }

    public function testOffsetRussian() {
        $this->populateWorld("/^Я ввожу складываю (\d+) и (\d+)$/");
        $sStep = "Я ввожу складываю 1 и 2";

        $this->assertEquals(
            $this->generateExpectation(array( array('val' => '1', 'pos' => 18), array('val' => '2', 'pos' => 22) )),
            $this->cuke->stepMatches($sStep)
        );
    }

    public function testOffsetEnglish() {
        $this->populateWorld("/^I add (\d+) to (\d+)$/");
        $sStep = "I add 1 to 2";

        $this->assertEquals(
            $this->generateExpectation(array( array('val' => '1', 'pos' => 6), array('val' => '2', 'pos' => 11) )),
            $this->cuke->stepMatches($sStep)
        );
    }

    private function populateWorld($regexp) {
        $this->cuke->aWorld = array('steps' =>
            array(
                array('regexp' => $regexp, 'filename' => self::FEATURE_FILE, 'startline' => self::STARTLINE)
            )
        );
    }

    private function generateExpectation(array $args) {
        return array(
            0 => 'success',
            1 => array(
                0 => array(
                    'id' => 0,
                    'args' => $args,
                    'source' => self::FEATURE_FILE . ':' . self::STARTLINE
                )
            )
        );
    }
}
