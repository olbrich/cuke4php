<?php

// TODO: allow for a custom install of PHPUnit at a non-standard location
require_once dirname(__FILE__) . "/../PHPUnit/PHPUnit/Framework.php";

foreach (glob(dirname(__FILE__) . "/*.php") as $sFilename) {
    require_once $sFilename;
}

?>