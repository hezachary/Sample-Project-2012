<?php
//################################################################################
//start definition
//################################################################################
error_reporting(E_ALL ^ E_NOTICE);
unset($CFG);
/* CONFIGURE THESE AS NEEDED */
// 00 site vars
$strFile = str_replace('\\','/', __FILE__);
$aryTmp = explode('/', $strFile);
$CFG->dirroot   = implode('/',array_splice($aryTmp, 0, sizeof($aryTmp) - 1)).'/';
require_once($CFG->dirroot.'includes/config.php');
//$strFile = realpath(__FILE__);
//$aryTmp = explode('/', $strFile);
//$CFG->dirroot   = dirname(realpath(__FILE__)).'/';
//require_once($CFG->dirroot.'includes/config.php');
?>