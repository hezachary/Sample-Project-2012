<?php
require_once 'front_config.php';

global $CFG;

    list($blnSuccess, $aryAssignData, $strTemplateName, $aryExtra) = service::page_load('home', $_REQUEST);
    $strHTMLExport = '';
    if($strTemplateName){
        $objSmarty = new smarty_page();
        $objSmarty->assign('CFG', $CFG);
        $objSmarty->assign('SECTION', $_REQUEST['section']);
        $objSmarty->assign('blnSuccess', $blnSuccess);
        $objSmarty->assign($aryAssignData);
        $objSmarty->assign('aryExtra', $aryExtra);
        $strHTMLExport = $objSmarty->fetch($strTemplateName);
    }
    $objExport = new stdClass();
    $objExport->success = $blnSuccess;
    $objExport->html = $strHTMLExport;
    if(is_array($aryExtra)){
        foreach($aryExtra as $strKey => $value){
            $objExport->$strKey = $value;
        }
    }
    echo !$aryExtra['no_ajax'] ? json_encode($objExport) : $objExport->html;

exit();

?>
