<?php
require_once('../front_config.php');


$db= xpMysql::conn();
$q = $_REQUEST;

$category = $db->q("SELECT DISTINCT name FROM  xparchive WHERE 1 ");


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
<h3>Archive</h3>
<form >
<div>
<h5>Category</h5>
<select name='category' onchange="this.form.key[0].selected = true; this.form.date[0].selected = true; this.form.submit();">
<option  value="" >select a category</option>
<?php
foreach ($category as $k=>$n){
	echo "<option value='{$n['name']}'  ".($q['category']==$n['name'] ?'selected' :'').">{$n['name']}</option>\n";
}
?>
</select>
</div>
<div>
<?php

	$key = $db->q("SELECT DISTINCT xparchive.key FROM  xparchive WHERE name='".mysql_escape_string($q['category'])."' ");
	?>
<h5>Key Value</h5>
<select name='key' onchange="this.form.date[0].selected = true; this.form.submit();">
<option value="" >select a key</option>
<?php
foreach ($key as $k=>$n){
	echo "<option value='{$n['key']}'  ".($q['key']==$n['key'] ?'selected' :'').">{$n['key']}</option>\n";
}
?>
</select>
</div>
<div>
<?php

	$date = $db->q("SELECT date FROM xparchive WHERE name='".mysql_escape_string($q['category'])."'  AND xparchive.key='".mysql_escape_string($q['key'])."' ");
?>

<h5>Date</h5>
<select name='date' onchange="this.form.submit();">
<option value="" >select a date</option>
<?php
foreach ($date as $k=>$n){
	echo "<option value='{$n['date']}'  ".($q['date']==$n['date'] ?'selected' :'').">{$n['date']}</option>\n";
}
?>
</select>
</div>
<hr class="clear" />
<?php

	if($q['date']){
		$archive = $db->q("SELECT * FROM xparchive WHERE  name='".mysql_escape_string($q['category'])."'  AND  xparchive.key='".mysql_escape_string($q['key'])."' AND date='".(mysql_escape_string($q['date']))."'" );
		_debug($archive);
	}
?>


