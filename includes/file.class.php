<?php
class file extends image{
    
    public static function retrieveUploadLocation(){
        global $CFG;
        return empty($CFG->section)?($CFG->data.'main/'):($CFG->data.$CFG->section.'/');
    }
    
    public static function removeFile($strFileName, $arySizeList = array(), $strLocation = null){
        $strLocation = empty($strLocation)?self::retrieveUploadLocation():$strLocation;
        $strFileName = basename($strFileName);
        @unlink($strLocation.$strFileName);
        if(is_array($arySizeList) && sizeof($arySizeList) > 0){
            foreach ($arySizeList as $strSizeName => $arySize){
                @unlink($strUploadPath.$strFileName.'_'.$strSizeName);
            }
        }
    }
    
    public static function fileUpload($strNewFileName = null, $aryValidatorList = array()){
        if($strNewFileName == null){
            $strNewFileName = uniqid('', true);
        }
        
        //1. upload file
        global $CFG;
    
        $aryMsg = array();
        $strType = '';
        $strLocation = '';
        
        $objUpload = new Zend_File_Transfer_Adapter_Http();
        
        if(!is_array($aryValidatorList) || sizeof($aryValidatorList) == 0){
            $aryValidatorList = $CFG->aryFileUploadValidator;
        }
    
        foreach ($aryValidatorList as $aryValidator){
            if(isset($aryValidator[2])){
                $objUpload->addValidator($aryValidator[0], $aryValidator[1], $aryValidator[2]);
            }else{
                $objUpload->addValidator($aryValidator[0], $aryValidator[1]);
            }
        }
            
        $aryFileInfoList = $objUpload->getFileInfo();
        
        foreach($aryFileInfoList as $aryFileInfo){
            $strFileNameOrg = $aryFileInfo['name'];
        }
        if (!$objUpload->isValid()) {
            $aryMsg['file'] = $objUpload->getMessages();
            return array($aryMsg, $objUpload, $strLocation, $strType);
        }
        $strLocation = self::retrieveUploadLocation();
        $objUpload->addFilter('Rename', array('target' => $strLocation.$strNewFileName, 'overwrite' => true));
        
        if (!$objUpload->receive()) {
            $aryMsg['file'] = $objUpload->getMessages();
            return array($aryMsg, $objUpload, $strLocation, $strType);
        }
        
        //$strType = strtolower($objUpload->getMimeType());
        $strType = $strType?$strType:strtolower(substr($strFileNameOrg, strrpos($strFileNameOrg, '.') + 1));
        
        switch ($strType){
            case 'jpeg':
            case 'jpg':
                $strType = 'image/jpeg';
                break;
            case 'gif':
            case 'png':
                $strType = 'image/'.$strType;
                break;
            case 'swf':
                $strType = 'application/x-shockwave-flash';
                break;
            case 'flv':
                $strType = 'video/x-flv';
                break;
            case 'mov':
                $strType = 'video/quicktime';
                break;
            case 'f4v':
                $strType = 'video/mp4';
                break;        
            default:
                break;
        }
        
        return array($aryMsg, $objUpload, $strLocation, $strType, $strFileNameOrg);
    }
}
?>