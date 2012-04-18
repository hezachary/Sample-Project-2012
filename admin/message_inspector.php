<?php
require_once('../front_config.php');


$db= xpMysql::conn();
$q = $_REQUEST;

$category = $db->q("SELECT name,tblResident.id FROM  tblResident,message_box WHERE message_box.resident_id = tblResident.id ");


?>
<style>
div{
float:left;
margin:0 56px 20px 0;
}
.clear{
clear:both;
}
</style>
<h3>Message</h3>
<form >
<div>
<h5>Persion</h5>
<select name='person' onchange="this.form.date_from[0].selected = true; this.form.date_to[0].selected = true;  this.form.submit();">
<option  value="" >select a person</option>
<?php
foreach ($category as $k=>$n){
	echo "<option value='{$n['id']}'  ".($q['person']==$n['id'] ?'selected' :'').">".sprintf("%07d",$n['id'])." - {$n['name']}</option>\n";
}
?>
</select>
</div>
<div>
<?php

	$date = $db->q("SELECT DISTINCT SUBSTRING(date,1,10) as date FROM message_box WHERE resident_id='".mysql_escape_string($q['person'])."' ");
?>

<h5>Date From</h5>
<select name='date_from' onchange="">
<option value="" >select a date</option>
<?php
foreach ($date as $k=>$n){
	echo "<option value='{$n['date']}'  ".($q['date_from']==$n['date'] ?'selected' :'').">{$n['date']}</option>\n";
}
?>
</select>
</div>
<div>

<h5>Date To</h5>
<select name='date_to' onchange="this.form.submit();">
<option value="" >select a date</option>
<?php
foreach ($date as $k=>$n){
	echo "<option value='{$n['date']}'  ".($q['date_to']==$n['date'] ?'selected' :'').">{$n['date']}</option>\n";
}
?>
</select>
</div>
<hr class="clear" />
<?php
$from = xpDate::returnDate($q['date_from']);
$to = xpDate::next_date(xpDate::returnDate($q['date_to']));
	if($q['date_to']){
		$archive = $db->q("SELECT * FROM message_box WHERE  resident_id='".mysql_escape_string($q['person'])."'  AND date>='$from' AND date<'$to'" );
		_debug($archive);
	}
?>


