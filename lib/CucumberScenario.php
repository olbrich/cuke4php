<?php
class CucumberScenario {

    // provide a place we can store data
    public $aGlobals = array();

    private $aWorld;

    function __construct($_aWorld = array()) {
        $this->aWorld = $_aWorld;
    }

    function invokeBeforeHooks($aTags) {
        foreach ($this->aWorld['before'] as $aBeforeHook) {
            if (count(array_intersect($aTags, $aBeforeHook['tags'])) > 0) {
                $oStep = new $aBeforeHook['class'](&$this->aGlobals);
                $oResult = $oStep->$aBeforeHook['method']();
                if ($oResult === false) {
                    return array('failure');
                }
            }
        }
        return array('success');
    }

    function invokeAfterHooks($aTags) {
        foreach ($this->aWorld['after'] as $aAfterHook) {
            if (count(array_intersect($aTags, $aAfterHook['tags'])) > 0) {
                $oStep = new $aAfterHook['class'](&$this->aGlobals);
                $oResult = $oStep->$aAfterHook['method']();
                if ($oResult === false) {
                    return array('failure');
                }
            }
        }
        return array('success');
    }

    function invoke($aArgs) {

    }

    

}
?>