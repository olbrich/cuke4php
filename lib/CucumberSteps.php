<?php

class CucumberSteps {

    public function __construct() {
        $reflection = new ReflectionClass(get_class($this));
        $aMethods = $reflection->getMethods();
        foreach ($aMethods as $method) {
            $comment = $method->getDocComment();
            $matches = array();
            preg_match("/(?:Given|When|Then) (.+)$/im", $comment, $matches);
            array_push($this->aSteps, array($matches[1], array($method->class, $method->name)));
        }
    }


}

?>