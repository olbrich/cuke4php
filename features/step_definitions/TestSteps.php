<?php
/**
 * @package Cuke4Php
 */
 
/**
 * @package Cuke4Php
 */ 
class TestSteps extends CucumberSteps {

    /**
     * Given /^successful$/
     **/
    public function stepSuccessful() {}

    /**
     * Given /^incomplete$/
     */
    public function stepIncomplete() {
        self::markTestIncomplete('incomplete');
    }

    /**
     * Given /^skipped$/
     */
    public function stepSkipped() {
        self::markTestSkipped('skipped');
    }

    /**
     * Given /^pending$/
     */
    public function stepPending() {
        self::markPending('pending');
    }

    /**
     * Given /^a failed expectation$/
     */
    public function stepFailed() {
        self::assertEquals(true, false);
    }

    /**
     * Given /^an exception is thrown$/
     */
    public function stepException() {
        throw new TestException('Exception');
    }

    /**
     * Given /^"arg1" not equal to "arg2"$/
     */
    public function stepNotEqual($arg1,$arg2) {
        self::assertTrue($arg1 !== $arg2);
    }
		
		/**
		 * @one
		 */
		public function afterWithOneTag() {
			
		}
		
		/**
		 * 
		 */
		public function afterWithNoTags() {
			
		}
		
		/**
		 * @one
		 */
		public function beforeWithOneTag() {
			
		}
		
		/**
		 * 
		 */
		public function beforeWithNoTags() {
			
		}

}

?>