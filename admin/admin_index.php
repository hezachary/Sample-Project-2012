<?php
require_once('admin_config.php');
global $CFG; 
$objCurrentUser = user::get_handle();
if(!$objCurrentUser->ACL->admin['view']){
	header('Location: admin_login.php?error=1&link='.urlencode($_SERVER['QUERY_STRING']));
	exit;
}
require_once($CFG->mod_path.'admin/page_ext/common.php');
$strLoadPageFile = $CFG->mod_path.'admin/page_ext/'.basename($_REQUEST['section'].'.php');
if(file_exists($strLoadPageFile)){
    require_once($strLoadPageFile);
}

$section = '';
$page_file = null;
$aryMsg = array();
switch ($_REQUEST['section']){
	case 'player_list':
		$CFG->section = 'player_list';
        $aryCondition = array();
        $intPageNum = (int)$_REQUEST['page'];
        
        switch ($_REQUEST['mode']){
            case 'info':
        		$page_file = 'player_info.tpl';
                $aryOrder = $_REQUEST['order']?array($_REQUEST['order'].' '.($_REQUEST['asc']=='false'?'DESC':'ASC')):array('timestamp DESC', 'id DESC');
                $page_content = fnPlayerInfoPageContent((int)$_REQUEST['resident_id'], $aryCondition, $intPageNum, $aryOrder);
                break;
            default:
        		$page_file = 'player_list.tpl';
                $aryOrder = $_REQUEST['order']?array($_REQUEST['order'].' '.($_REQUEST['asc']=='false'?'DESC':'ASC')):array('created DESC', 'id DESC');
        		$page_content = fnPlayerListPageContent($aryCondition, $intPageNum, $aryOrder);
                break;
        }
		break;
	case 'order_list':
		$CFG->section = 'order_list';
		$page_file = 'order_list.tpl';
        $aryCondition = array();
        $intPageNum = (int)$_REQUEST['page'];
        $aryOrder = $_REQUEST['order']?array($_REQUEST['order'].' '.($_REQUEST['asc']=='false'?'DESC':'ASC')):array('timestamp DESC', 'id DESC');
        $page_content = fnOrderListPageContent($aryCondition, $intPageNum, $aryOrder);
		break;
	case 'map_editor':
        if($objCurrentUser->ACL->map_edit['view']){
        	$CFG->section = 'map_editor';
    		$page_file = 'map.tpl';
            $aryCondition = array();
            $intPageNum = (int)$_REQUEST['page'];
            $aryOrder = $_REQUEST['order']?array($_REQUEST['order'].' '.($_REQUEST['asc']=='false'?'DESC':'ASC')):array('timestamp DESC', 'id DESC');
            $page_content = fnMapEditorPageContent($aryCondition, $intPageNum, $aryOrder);
        }
		break;
	case 'celebrity_house':
        if($objCurrentUser->ACL->map_edit['view']){
        	$CFG->section = 'celebrity_house';
    		$page_file = 'celebrity_house.tpl';
            $page_content = fnCelebrityHousePageContent();
        }
		break;
	case 'log_manager':
		$CFG->section = 'log_manager';
		$page_file = 'log_manager.tpl';
        $aryCondition = array();
        $intPageNum = (int)$_REQUEST['page'];
        $aryOrder = $_REQUEST['order']?array($_REQUEST['order'].' '.($_REQUEST['asc']=='false'?'DESC':'ASC')):array('timestamp DESC', 'id DESC');
        $page_content = fnPageContent($aryCondition, $intPageNum, $aryOrder);
		break;
	case 'cache_manager':
		$CFG->section = 'cache_manager';
		$page_file = 'cache_manager.tpl';
        $aryCondition = array();
        $intPageNum = (int)$_REQUEST['page'];
        $aryOrder = $_REQUEST['order']?array($_REQUEST['order'].' '.($_REQUEST['asc']=='false'?'DESC':'ASC')):array('timestamp DESC', 'id DESC');
        $page_content = fnCacheManagerPageContent($aryCondition, $intPageNum, $aryOrder);
		break;
	case 'resident_info_csv':
        $intResidnet = (int)$_REQUEST['resident_id'];
        
        $aryOrderCondtion = array();
        list($aryOrderCondtion, $strSearchField, $strData) = fnRetrieveSearchField($aryOrderCondtion, $_REQUEST, db_order::strTable);
        
        if($intResidnet){
            $fileName = 'resident_'.(int)$_REQUEST['resident_id'].'_order_history_'.date('Ymd').'.csv';
            $aryOrderCondtion['resident_id'] = (int)$_REQUEST['resident_id'];
        }else{
            $fileName = 'order_history_'.date('Ymd').'.csv';
        }
        $aryOrderCondtion = !is_array($aryOrderCondtion) ? array():$aryOrderCondtion;
        
        $aryOrder = array('timestamp DESC', 'id DESC');
        $aryOrderSelect = array(            'id',
                                            'FirstName',
                                            'LastName',
                                            'Email',
                                            'CardHoldersName',
                                            'CardMaskedNumber',
                                            'PlanName',
                                            'Cost',
                                            'resident_id',
                                            'facebook_id',
                                            'timestamp',
                                            'trans_no',
                                            'order_status',
                                            'eway_return_msg',);
        $aryResultList = db_order::retrieveList(db_order::strTable, $aryOrderCondtion, 0, -999999999, $aryOrderSelect, false, $aryOrder);
        
		$aryTitleList = array();
		if(is_array($aryResultList)&&sizeof($aryResultList)>0){
			$aryTitleList = array_keys($aryResultList[0]);
			foreach ($aryTitleList as $key => $title){
				$aryTitleList[$key] = ucwords(str_replace('_', ' ', $title));
			}
		}else{
			$aryResultList = array();
			$aryTitleList = array();
		}
		$objCSV = new csv();
		$objCSV->fnStartCSV($fileName);
		$objCSV->fnResetCSV();
		$objCSV->fnUnsetInfo();
		$objCSV->fnCreateCSV(array($aryTitleList));
		$objCSV->fnCreateCSV($aryResultList);
		$objCSV->fnShow();
		exit;
		break;
	case 'csv_email_log':
		$aryOrder = array('r.id ASC', 'e.id ASC');
		$aryCondition = array();
		
		$fileName = 'email_log_'.date('Ymd').'.csv';
		$aryCondition['mode'] = $_REQUEST['mode'];
		
		$aryResultList = email_log::retrieveEmailLogList($aryCondition, $aryOrder);
		
		$aryTitleList = array();
		if(is_array($aryResultList)&&sizeof($aryResultList)>0){
			$aryTitleList = array_keys($aryResultList[0]);
			foreach ($aryTitleList as $key => $title){
				$aryTitleList[$key] = ucwords(str_replace('_', ' ', $title));
			}
		}else{
			$aryResultList = array();
			$aryTitleList = array();
		}
		$objCSV = new csv();
		$objCSV->fnStartCSV($fileName);
		$objCSV->fnResetCSV();
		$objCSV->fnUnsetInfo();
		$objCSV->fnCreateCSV(array($aryTitleList));
		$objCSV->fnCreateCSV($aryResultList);
		$objCSV->fnShow();
		exit;
		break;
	case 'csv':
		$aryOrder = array(db_resident::strTable.'.created DESC', db_resident::strTable.'.id DESC');
		$aryCondition = array();
		
		$fileName = 'resident_'.date('Ymd').'.csv';
		
        $aryCondition['role_id'][] = array('role_id','>','10');
        $arySelect = array(                     'id',
                                                'role_id',
                                                'name',
                                                'credit',
                                                'facebook_id',
                                                'email',
                                                'notification',
                                                'currentlogin',
                                                'currentip',
                                                'current_login_session_id',
                                                'lastlogin',
                                                'lastip',
                                                'last_login_session_id',
                                                'created');
        
		list($aryCondition, $strSearchField, $strData) = fnRetrieveSearchField($aryCondition, $_REQUEST, db_resident::strTable);
        
        $aryCondition = !is_array($aryCondition) ? array():$aryCondition;
		$aryResultList = db_resident::retrieveList(db_resident::strTable, $aryCondition, $intPageNum, -999999999, $arySelect, false, $aryOrder);
        
		$aryTitleList = array();
		if(is_array($aryResultList)&&sizeof($aryResultList)>0){
			$aryTitleList = array_keys($aryResultList[0]);
			foreach ($aryTitleList as $key => $title){
				$aryTitleList[$key] = ucwords(str_replace('_', ' ', $title));
			}
		}else{
			$aryResultList = array();
			$aryTitleList = array();
		}
		$objCSV = new csv();
		$objCSV->fnStartCSV($fileName);
		$objCSV->fnResetCSV();
		$objCSV->fnUnsetInfo();
		$objCSV->fnCreateCSV(array($aryTitleList));
		$objCSV->fnCreateCSV($aryResultList);
		$objCSV->fnShow();
		exit;
		break;
	default:
		if(isset($PAGE[$_REQUEST['section']])){
			$CFG->section = $_REQUEST['section'];
			$CURRENT_PAGE_TPL_PATH = $PAGE[$CFG->section]->tpl_path;
			$page_file = $CURRENT_PAGE_TPL_PATH.$PAGE[$CFG->section]->tpl;
			include('mod/'.$CFG->section.'/'.basename($PAGE[$CFG->section]->default_php));
		}else{
		}
		break;
	
}


$objLoginLog = new login_log();

$objLoginLog->date = date('Y-m-d');
$objLoginLog->retrieveLoginLog();
$aryLoginLog['today'] = get_object_vars($objLoginLog);

$objLoginLog->date = date('Y-m-d', strtotime("yesterday"));
$objLoginLog->retrieveLoginLog();
$aryLoginLog['yesterday'] = get_object_vars($objLoginLog);

$objExport = new smarty_admin();
$objExport->assign('CFG', $CFG);
$objExport->assign('USER', $objCurrentUser);
$objExport->assign('PAGE', $PAGE);

$objExport->assign('msg', $aryMsg);
$objExport->assign('login_log', $aryLoginLog);
$objExport->assign('page_file', $page_file);
$objExport->assign('page_content', $page_content);

$objExport->display('main.tpl');	

exit;
?>