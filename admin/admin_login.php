<?php
require_once('admin_config.php');
$strMsg = '';
if(isset( $_REQUEST['logout'] )){
	user::unsetUser();
	header('Location: admin_login.php');
	exit;
}
if(isset($_POST['username']) || isset($_POST['password'])){
	$strUsername = $_POST['username'];
	$strPassword = $_POST['password'];

	$objUser = new user();
	$objUser->username = $strUsername;
	$objUser->pw_org = $strPassword;

	$objLoginLog = new login_log();
	if($objUser->login()){
		$objLoginLog->maintainLoginLog('successed');
        
		header('Location: admin_index.php?'.($_REQUEST['link']?$_REQUEST['link']:'section=register'));
		exit;
	}else{
		$objLoginLog->maintainLoginLog('failed');
		$strMsg = 'ID or PW is not Exist! ';
	}
}
$strMsg .= 'Please login as admin!';
$page_content['msg'] = $strMsg;
$page_content['link'] = $_REQUEST['link'];

$objExport = new smarty_admin();
$objExport->assign('CFG', $CFG);
$objExport->assign('page_content', $page_content);

$objExport->assign('page_file', 'login.tpl');
$objExport->display('main.tpl');	

exit;
?>