<?php
require_once 'front_config.php';

$strMode = basename($_REQUEST['mode']);

$strMode = file_exists($CFG->frontend.'/page.'.$strMode.'.tpl') ? $strMode : 'home';
list($blnSuccess, $aryAssignData, $strTemplateName, $aryExtra) = service::page_load($strMode, $_REQUEST);
//_d($aryWinnerAssignData,1,1);

$objExport = new smarty_page();
$objExport->assign('CFG', $CFG);
$objExport->assign('section', $strMode);
$objExport->assign('page_file', 'page.'.$strMode.'.tpl');
$objExport->assign($aryAssignData);

$objExport->display('page.tpl');
