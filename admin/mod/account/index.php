<?php
require_once 'account.class.php';
$CFG->section = 'account';
$objCurrentUser = user::get_handle();
if($_POST['save']){
	$page_content['account']['validation'] = array();
	if(	empty($objCurrentUser->password)){
		$page_content['account']['validation']['password']= 'Session expired, please login and try again!';		
	}
	if(	$objCurrentUser->password != account::convertPassword($_POST['old_password'])){
		$page_content['account']['validation']['old_password']= 'Wrong Password';
	}

	$_POST['new_password'] = trim($_POST['new_password']);
	if(	strlen($_POST['new_password']) < 6 || strlen($_POST['new_password']) > 32){
		$page_content['account']['validation']['new_password']= 'Password length is between 6~32';
	}
	if(	$_POST['new_password'] != $_POST['con_password']){
		$page_content['account']['validation']['con_password']= 'Password is not match';
	}
	if(sizeof($page_content['account']['validation']) == 0){
		$objAccount = new account();
		$objAccount->setId($objCurrentUser->id);
		$objAccount->pw_org = $_POST['new_password'];
		$objAccount->updatePw();
		header('Location: '.$CFG->www.'admin/admin_index.php?section='.$CFG->section.'&updated=true');
		exit;
	}
}

if($_REQUEST['updated']){
	$page_content['account']['msg']= 'Password is updated!';
}
?>