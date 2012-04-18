<?php
class service
{
    public static function page_load($strMode, $aryRequest = array()){
        $blnSuccess = true;
        $aryAssignData = array();
        $aryExtra = array();
        switch($strMode){
            case 'retrieve_winner':
                //list($blnSuccess, $aryAssignData['student'], $strTemplateName, $aryExtra) = self::load_detail(array('winner' => 'student'));
                //list($blnSuccess, $aryAssignData['professional'], $strTemplateName, $aryExtra) = self::load_detail(array('winner' => 'professional'));
                break;
            case 'detail':
                //list($blnSuccess, $aryAssignData, $strTemplateName, $aryExtra) = self::load_detail($aryRequest);
                break;
            default:
                //list($blnSuccess, $aryAssignData, $strTemplateName, $aryExtra) = self::load_result($aryRequest);
                break;
        }
        
        return array($blnSuccess, $aryAssignData, $strTemplateName, $aryExtra);
    }
    
}