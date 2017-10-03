<?php

/* 1.1.8 */
error_reporting(0);
session_start();


define("DS", DIRECTORY_SEPARATOR);
define("ROOT", realpath(dirname(__FILE__)));

include 'config.php';
include 'engine/RexPHP.class.php';


$rex = new Rex();

$rex->helper('request');
if (isset($rex->request->get['trace'])) {
    $trace = $rex->request->get['trace'];
} else if (DEFAULT_PAGE != "") {
    $trace = DEFAULT_PAGE;
} else {
    trigger_error('There is no DEFAULT TRACE.Please change it in <u>config.php</u>');
    exit();
}
//start the engine
$rex->call($trace);

?>
