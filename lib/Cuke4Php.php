<?php
set_time_limit(0);
require_once("CucumberScenario.php");
require_once("CucumberSteps.php");

/**
 *  Cuke4Php implements the Cucumber wire protocol for PHP
 */
class Cuke4Php {
    public $iPort = 54321;
    private $bRun;
    private $oSocket;
    private $oScenario;
    private $aStepClasses;
    private $aWorld = array(
        'steps' => array(),
        'before' => array(),
        'after' => array()
    );

    function __construct() {
        // TODO: Load step definitions
        $predefined_classes = get_declared_classes();
        foreach (glob("../features/**/*.php") as $sFilename) {
            require $sFilename;
        }
        $this->aStepClasses = array_values(array_diff(get_declared_classes(), $predefined_classes));
        foreach ($this->aStepClasses as $sClass) {
            $oReflection = new ReflectionClass($sClass);
            $aMethods = $oReflection->getMethods();
            foreach ($aMethods as $oMethod) {
                $sComment = $oMethod->getDocComment();
                $aMatches = array();
                $aMethod = array();
                preg_match("/(@.+)/im", $sComment, $aMatches);
                $aMethod['tags']= explode(" ", str_replace("@","",$aMatches[1]));
                $aMethod['method'] = $oMethod->name;
                $aMethod['class'] = $oMethod->class;
                if (substr($oMethod->name,0,4) === "step") {
                    preg_match("/(?:Given|When|Then) (.+)$/im", $sComment, $aMatches);
                    array_push($this->aWorld['steps'], array($aMatches[1], $aMethod));
                    continue;
                }
                if (substr($oMethod->name,0,6) === "before") {
                    array_push($this->aWorld['before'], $aMethod);
                    continue;
                }
                if (substr($oMethod->name,0,5) === "after") {
                    array_push($this->aWorld['after'], $aMethod);
                    continue;
                }
            }
        }
    }

    function __destruct() {
        if (isset($this->oSocket) && $this->oSocket) {
            print "Closing socket\n";
            socket_close($this->oSocket);
        }
    }

    function run() {
        $this->oSocket = socket_create_listen($this->iPort);
        $this->bRun = true;
        while ($this->bRun && ($connection = socket_accept($this->oSocket))) {
            socket_getpeername($connection, $raddr, $rport);
            print "Received Connection from $raddr:$rport\n";
            while ($this->bRun && ($input = socket_read($connection, 1024))) {
                $data = trim($input);
                if ($data !== "") {
                    $output = json_encode($this->process($data)) . "\n";
                    print "output = $output\n";
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
                //var_dump($aCommand, $sAction);
                switch ($sAction) {
                    case 'begin_scenario':
                        return $this->beginScenario($sData->tags);
                        break;
                    case 'step_matches':
                        return $this->stepMatches($sData);
                        break;
                    case 'invoke':
                        return $this->invoke($sData);
                        break;
                    case 'end_scenario':
                        return $this->endScenario($sData);
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

    /*
     * run any before hooks
     */
    function beginScenario($aTags) {
        print("Begin Scenario\n");
        $this->oScenario = new CucumberScenario($this->aWorld);
        return $this->oScenario->invokeBeforeHooks($aTags);
    }

    /*
     * match steps
     */
    function stepMatches($aSteps) {
        print("stepMatches\n");
        var_dump($aSteps);
        return array('success', array());
    }

    /*
     * invoke any step definitions
     */
    function invoke($aArgs) {
        print("Invoke\n");
        var_dump($aArgs);
        return array('pending', array("Not Implemented"));
    }

    /*
     * run any after hooks
     */
    function endScenario($aTags) {
        print("End Scenario\n");
        $oResult = $this->oScenario->invokeAfterHooks($aTags);
        $this->oScenario = null;
        return $oResult;
    }

    /*
     * return a template for an undefined step
     */
    function snippetText($aSnippet) {
        print("Snippet Text\n");
        $sMethodName = "step" . str_replace(" ","", ucwords(preg_replace("/\W+/"," ",preg_replace("/\"[^\"]*\"/","Parameter",$aSnippet->step_name))));
        $count = 0;
        $aParams = array();
        $sStepName = preg_replace("/\"[^\"]*\"/","\"([^\"]*)\"",preg_quote($aSnippet->step_name), -1, &$count);
        for ($param=1; $param<=$count; $param++) {
            $aParams[]= "arg$param";
        }
        if ($aSnippet->multiline_arg_class !== "") {
            $aParams[]= "table";
        }
        $sParams = implode(",", $aParams);
        $sMethodBody = <<<EOT

/**
* tags:
* {$aSnippet->step_keyword}/^$sStepName$/
**/
public function $sMethodName($sParams) {
    return array('pending');
}
EOT;
        return array('success', $sMethodBody);
}
}
?>