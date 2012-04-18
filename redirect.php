<?php
require_once 'front_config.php';
$strReq = substr($_SERVER['REDIRECT_URL'], strlen($CFG->www));
$aryReqString = explode('/', $strReq);

$_REQUEST['mode'] = array_shift($aryReqString);
$i = 0;
while($i < sizeof($aryReqString) && strlen($aryReqString[$i + 1])){
    if(!isset($_REQUEST[$aryReqString[$i]])){
        $_REQUEST[$aryReqString[$i]] = $aryReqString[$i+1];
    }
    $i += 2;
}
require_once 'index.php';
?>