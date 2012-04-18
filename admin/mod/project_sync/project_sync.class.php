<?php

class project_sync{
    const strTable = 'user';
    
    public static function setCFG(){
        global $CFG, $PAGE, $config;
        $CFG->project_sync = $PAGE['project_sync'];
        
        if(is_array($CFG->svn)){
            foreach($CFG->svn as $strParam => $arySvnSetting){
                $CFG->project_sync->svn[$strParam] = $arySvnSetting;
            }
        }
        
        foreach($CFG->project_sync->svn['folder_list'] as $strFolderPath => $arySvnSetting){
            $strHtaccessFilePath = $CFG->dirroot. ($arySvnSetting['htaccess'] ? $arySvnSetting['htaccess'] : $strFolderPath.'/.htaccess');
            if(file_exists($strHtaccessFilePath)){
                $CFG->project_sync->svn['folder_list'][$strFolderPath]['htaccess'] = file_get_contents($strHtaccessFilePath);
            }
        }

    }
    
    public static function populateInstallPHP(){
        global $CFG, $CURRENT_PAGE_TPL_PATH;
        $objExport = new smarty_admin();
        $objExport->template_dir = $CURRENT_PAGE_TPL_PATH;
        $objExport->assign('CFG', $CFG);
        
        return $objExport->fetch('install.tpl');
    }
    
    public static function retrieveSVNLog($intLimit = 50, $aryReturnLog = array()){
        global $CFG;
        exec('svn log '.$CFG->project_sync->svn['url'].' --username '.$CFG->project_sync->svn['id'].' --password '.$CFG->project_sync->svn['pw'].' --no-auth-cache --limit '.$intLimit, $aryReturnLog);
        return $aryReturnLog;
    }
    
    public static function updateVersion($intVersion = null, $aryReturnLog = array()){
        global $CFG;
        $intVersion = (int)$intVersion;
        exec('svn update '.$CFG->dirroot.' --username '.$CFG->project_sync->svn['id'].($intVersion ? '':'--revision '.$intVersion).' --password '.$CFG->project_sync->svn['pw'].' --no-auth-cache', $aryReturnLog);
        return $aryReturnLog;
    }
}

?>