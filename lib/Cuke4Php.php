<?php
set_time_limit(0);


/**
 * @param string $pattern
 * @param int $flags
 * @param string $path
 * @return array
 */
function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}

/**
 *  Cuke4Php implements the Cucumber wire protocol for PHP
 *
 * http://wiki.github.com/aslakhellesoy/cucumber/wire-protocol
 */
class Cuke4Php {
    public $iPort;
    private $bRun;
    private $oSocket;
    private $oScenario;
    private $aStepClasses;
    private $aWorld = array(
        'steps' => array(),
        'before' => array(),
        'after' => array()
    );

    function __construct($_sFeaturePath, $_iPort = 16816) {
        if ($_iPort > 0) {
            $this->iPort = $_iPort;
        } else {
            $this->iPort = 16816;
        }

        $aPredefinedClasses = get_declared_classes();
        foreach (rglob("*.php", 0,  $_sFeaturePath . "/support") as $sFilename) {
            require_once $sFilename;
        }
        require_once "Cucumber.php";
        foreach (rglob("*.php", 0,  $_sFeaturePath) as $sFilename) {
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
                $aMethod['tags'] = explode(" ", str_replace("@", "", $aMatches[1]));
                if (substr($oMethod->name, 0, 6) === "before") {
                    $this->aWorld['before'][] = $aMethod;
                    continue;
                }
                if (substr($oMethod->name, 0, 5) === "after") {
                    $this->aWorld['after'][] = $aMethod;
                    continue;
                }
            }
        }
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
            print "Closing socket\n";
            socket_close($this->oSocket);
        }
    }

    function run() {
        print "Listening to port $this->iPort\n";
        $this->oSocket = socket_create_listen($this->iPort);
        $this->bRun = true;
        while ($this->bRun && ($connection = socket_accept($this->oSocket))) {
            socket_getpeername($connection, $raddr, $rport);
            print "Received Connection from $raddr:$rport\n";
            while ($this->bRun && ($input = socket_read($connection, 1024 * 4))) {
                $data = trim($input);
                if ($data !== "") {
                    $output = json_encode($this->process($data)) . "\n";
                    socket_write($connection, $output);
                }
            }
            print "closing connection\n";
            socket_close($connection);
            sleep(1);
        }
    }

    function process($sInput) {
        switch ($sInput) {
            case "quit":
            case "bye":
                $this->bRun = false;
                print "Quitting\n";
                return array('failure');
                break;
            default:
                $aCommand = json_decode($sInput);
                $sAction = $aCommand[0];
                $sData = $aCommand[1];
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
* {$aSnippet->step_keyword}/^$sStepName$/
**/
public function $sMethodName($sParams) {
    self::markPending();
}
EOT;
        return array('success', $sMethodBody);
    }
}

?>