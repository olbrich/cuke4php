<?php
/**
 * @package Cuke4Php
 */

set_time_limit(0);


/**
 *  Cuke4Php implements the Cucumber wire protocol for PHP
 *
 * http://wiki.github.com/aslakhellesoy/cucumber/wire-protocol
 * @package Cuke4Php
 */
class Cuke4Php {
    public $iPort;
    private $bRun;
    private $oSocket;
    private $oScenario;
    private $aStepClasses;
    public $aWorld = array(
        'steps' => array(),
        'before' => array(),
        'after' => array(),
        'transform' => array()
    );

    function __construct($_sFeaturePath, $_iPort = 16816) {
        if (is_file($_sFeaturePath)) {
          $_sFeaturePath = dirname($_sFeaturePath);
        }
        if ($_iPort > 0) {
            $this->iPort = $_iPort;
        } else {
            $this->iPort = 16816;
        }
                
        foreach (self::rglob("*.php", 0,  $_sFeaturePath . "/support") as $sFilename) {
            require_once $sFilename;
        }
        set_error_handler(
          array('PHPUnit_Util_ErrorHandler', 'handleError'),
          E_ALL | E_STRICT
        );
        
        require_once "Cucumber.php";
        foreach (self::rglob("*.php", 0,  $_sFeaturePath . "/step_definitions") as $sFilename) {
            require_once $sFilename;
        }
        $this->aStepClasses = CucumberSteps::getSubclasses();
        foreach ($this->aStepClasses as $sClass) {
            $oReflection = new ReflectionClass($sClass);
            $aMethods = $oReflection->getMethods();
            foreach ($aMethods as $oMethod) {
                $sComment = $oMethod->getDocComment();
                $aMatches = array();
                $aMethod = array();
                $aMethod['method'] = $oMethod->name;
                $aMethod['class'] = $oMethod->class;
                $aMethod['filename'] = $oMethod->getFileName();
                $aMethod['startline'] = $oMethod->getStartLine();
                if (substr($oMethod->name, 0, 4) === "step") {
                    preg_match("/(?:Given|When|Then) (.+)$/im", $sComment, $aMatches);
                    $aMethod['regexp'] = $aMatches[1];
                    $this->aWorld['steps'][] = $aMethod;
                    continue;
                }
                preg_match("/(@.+)/im", $sComment, $aMatches);
                if (array_key_exists(1, $aMatches)) {
                    $aMethod['tags'] = explode(" ", str_replace("@", "", $aMatches[1]));
                } else {
                    $aMethod['tags'] = array();
                }
                if (substr($oMethod->name, 0, 6) === "before") {
                    $this->aWorld['before'][] = $aMethod;
                    continue;
                }
                if (substr($oMethod->name, 0, 5) === "after") {
                    $this->aWorld['after'][] = $aMethod;
                    continue;
                }
                if (substr($oMethod->name, 0, 9) == "transform") {
                    preg_match("/(?:Transform) (.+)$/im", $sComment, $aMatches);
                    $aMethod['regexp'] = $aMatches[1];
                    $this->aWorld['transform'][] = $aMethod;
                    continue;
                }
            }
        }
    }

		/**
		 * @param string $pattern
		 * @param int $flags
		 * @param string $path
		 * @return array
		 * recursive glob utility function
		 */
		static function rglob($sPattern='*', $iFlags = 0, $sPath='') {
		    $aPaths=glob($sPath.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
		    $aFiles=glob($sPath.$sPattern, $iFlags);
		    foreach ($aPaths as $sPath) { $aFiles=array_merge($aFiles,self::rglob($sPattern, $iFlags, $sPath)); }
		    return $aFiles;
		}


    /**
     * @param  $oScenario
     * @return void
     */
    function setScenario($oScenario) {
        $this->oScenario = $oScenario;
    }

    function __destruct() {
        if (isset($this->oSocket) && $this->oSocket) {
            socket_close($this->oSocket);
        }
    }

    function run() {
        print "Cuke4Php listening on port $this->iPort\n";
        $this->oSocket = socket_create_listen($this->iPort);
        $this->bRun = true;
        while ($this->bRun && ($connection = socket_accept($this->oSocket))) {
            socket_getpeername($connection, $raddr, $rport);
            try {
                while ($this->bRun && ($input = socket_read($connection, 4096, PHP_NORMAL_READ))) {
                    $data = trim($input);
                    if ($data !== "") {
                        $output = json_encode($this->process($data)) . "\n";
                        if ($this->bRun) {
                          socket_write($connection, $output);
                        }
                    }
                }                
            } catch (Exception $e) {
               if (socket_last_error($connection) != 54) {
                   throw $e;
               };
            }
            socket_close($connection);
            sleep(1);
        }
    }

    function process($sInput) {
        switch ($sInput) {
            case "quit":
            case "bye":
                $this->bRun = false;
                return "Complete";
                break;
            default:
                $aCommand = json_decode($sInput);
                $sAction = $aCommand[0];
                $sData = new stdClass;
                $sData->tags = array();
                if (array_key_exists(1, $aCommand)) {
                    $sData = $aCommand[1];
                }
                switch ($sAction) {
                    case 'begin_scenario':
                        return $this->beginScenario($sData->tags);
                        break;
                    case 'step_matches':
                        return $this->stepMatches($sData->name_to_match);
                        break;
                    case 'invoke':
                        return $this->oScenario->invoke($sData->id, $sData->args);
                        break;
                    case 'end_scenario':
                        return $this->endScenario($sData->tags);
                        break;
                    case 'snippet_text':
                        return $this->snippetText($sData);
                    default:
                        print "Unknown Command: $sInput\n";
                        break;
                }
                return array('success');
                break;
        }
    }

    /**
     * @param  $aTags
     * @return array
     * run any before hooks for a scenario
     */
    function beginScenario($aTags) {
        $this->setScenario(CucumberScenario::getInstance($this->aWorld));
        return $this->oScenario->invokeBeforeHooks($aTags);
    }

    /**
     * @param  $sStep
     * @return array
     * when given a string, this method will return information about any step that matches it
     */
    function stepMatches($sStep) {
        $aSteps = array();
        for ($i = 0; $i < count($this->aWorld['steps']); $i++) {
            $aMatches = array();
            $aStep = $this->aWorld['steps'][$i];
            if (preg_match_all($aStep['regexp'], $sStep, $aMatches, PREG_OFFSET_CAPTURE)) {
                $aArgs = array();
                array_shift($aMatches);
                foreach ($aMatches as $aMatch) {
                    $aArgs[] = array('val' => $aMatch[0][0], 'pos' => $aMatch[0][1]);
                }
                $aSteps[] = array('id' => $i, 'args' => $aArgs, 'source' => $aStep['filename'] . ":" . $aStep['startline']);
            };
        }
        return array('success', $aSteps);
    }

    /**
     * @param  $aTags
     * @return array
     * run any after hooks for a scenario
     */
    function endScenario($aTags) {
        $oResult = $this->oScenario->invokeAfterHooks($aTags);
        $this->oScenario = null;
        return $oResult;
    }

    /**
     * @param  $aSnippet
     * @return array
     * return a template for an undefined step
     */
    function snippetText($aSnippet) {
        $sMethodName = "step" . str_replace(" ", "", ucwords(preg_replace("/\W+/", " ", preg_replace("/\"[^\"]*\"/", "Parameter", $aSnippet->step_name))));
        $count = 0;
        $aParams = array();
        $sStepName = preg_replace("/\"[^\"]*\"/", "\"([^\"]*)\"", preg_quote($aSnippet->step_name), -1, &$count);
        for ($param = 1; $param <= $count; $param++) {
            $aParams[] = "\$arg$param";
        }
        switch ($aSnippet->multiline_arg_class) {
            case "Cucumber::Ast::Table":
                $aParams[] = "\$aTable";
                break;
            case "Cucumber::Ast::PyString":
                $aParams[] = "\$sString";
                break;
            default:
        }

        $sParams = implode(",", $aParams);
        $sMethodBody = <<<EOT

/**
* {$aSnippet->step_keyword} /^$sStepName$/
**/
public function $sMethodName($sParams) {
    self::markPending();
}
EOT;
        return array('success', $sMethodBody);
    }
}

?>