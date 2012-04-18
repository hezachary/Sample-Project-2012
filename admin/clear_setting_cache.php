<?php
require_once('admin_config.php');
global $CFG; 
$objCurrentUser = user::get_handle();
if(!$objCurrentUser->ACL->admin['edit']){
	exit;
}
?>
<form action="" method="POST">
<input type="submit" name="save" value="Clear Element Cache" />
<?php
if($_POST['save']){
    $aryResidentElementTypeList = db_resident_field_building::retrieveResidentElementTypeList(null, true);
    foreach($aryResidentElementTypeList as $aryResidentElementType){
        db_resident_field_building::retrieveResidentElementByMapElementTypeIdList($aryResidentElementType['id'], true);
    }
    echo 'done';
}
?>
</form>