<?php
require_once 'project_sync.class.php';
$CFG->section = 'project_sync';
$objCurrentUser = user::get_handle();

project_sync::setCFG();

switch($_REQUEST['mode']){
    case 'self_install':
		header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=install.php');
        echo project_sync::populateInstallPHP();
		exit;
        break;
    case 'update':
        $page_content['project_sync']['msg'] = project_sync::updateVersion($_POST['version']);
        break;
}
$page_content['project_sync']['svn_log'] = project_sync::retrieveSVNLog();
?>