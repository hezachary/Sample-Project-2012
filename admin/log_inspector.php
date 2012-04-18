<?php
require_once('../front_config.php');


$db= xpMysql::conn();
$q = $_REQUEST;

$category = $db->q("SELECT DISTINCT name FROM  xplog WHERE 1 ");


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
<h3>LOG</h3>
<form >
<div>
<h5>Category</h5>
<select name='category' onchange="this.form.date_from[0].selected = true; this.form.date_to[0].selected = true;  this.form.submit();">
<option  value="" >select a category</option>
<?php
foreach ($category as $k=>$n){
	echo "<option value='{$n['name']}'  ".($q['category']==$n['name'] ?'selected' :'').">{$n['name']}</option>\n";
}
?>
</select>
</div>
<?php

	$date = $db->q("SELECT DISTINCT SUBSTRING(date,1,10) as date FROM xplog WHERE name='".mysql_escape_string($q['category'])."' ");
?>
<div>
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

<hr class="clear">
<?php
$from = xpDate::returnDate($q['date_from']);
$to = xpDate::next_date(xpDate::returnDate($q['date_to']));
	if($q['date_to']){
		$archive = $db->q("SELECT * FROM xplog WHERE  name='".mysql_escape_string($q['category'])."'  AND date>='$from' AND date<'$to'" );
		_debug($archive);
	}
?>


