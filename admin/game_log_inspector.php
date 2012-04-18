<?php
require_once('../front_config.php');


$db= xpMysql::conn();
$q = $_REQUEST;



	
$community = $db->q("SELECT DISTINCT id, name FROM  tblNeighbourhood WHERE 1 ");


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
<h3>Game Log</h3>
<form >
<div>
<h5>Community</h5>
<select name='community' onchange="this.form.player[0].selected = true; this.form.game[0].selected = true;  this.form.date_from[0].selected = true; this.form.date_to[0].selected = true;  this.form.submit();">
<option  value="" >select a neighbourhood</option>
<?php
foreach ($community as $k=>$n){
	echo "<option value='{$n['id']}'  ".($q['community']==$n['id'] ?'selected' :'').">".(sprintf("%07d",$n['id']))." - {$n['name']}</option>\n";
}
?>
</select>
</div>
<div>
<?php

$game = $db->q("SELECT game_id,name FROM  game WHERE 1 ");


?>

<h5>Game</h5>
<select name='game' onchange="this.form.player[0].selected = true; this.form.date_from[0].selected = true; this.form.date_to[0].selected = true;  this.form.submit();">
<option  value="" >select a game</option>
<?php
foreach ($game as $k=>$n){
	echo "<option value='{$n['game_id']}'  ".($q['game']==$n['game_id'] ?'selected' :'').">".(sprintf("%07d",$n['game_id']))." - {$n['name']}</option>\n";
}
?>
</select>
</div>

<div>

<?php

$player = $db->q("SELECT DISTINCT resident_id,tblResident.name  FROM  game_log,tblResident WHERE game_log.neighbourhood_id = '{$q['community']}' AND game_log.resident_id = tblResident.id AND game_id='".((int)$q['game'])."' ");


?>

<h5>Player</h5>
<select name='player' onchange=" this.form.date_from[0].selected = true; this.form.date_to[0].selected = true;  this.form.submit();">
<option  value="" >select a player</option>
<?php
foreach ($player as $k=>$n){
	echo "<option value='{$n['resident_id']}'  ".($q['player']==$n['resident_id'] ?'selected' :'').">".(sprintf("%07d",$n['resident_id']))." - {$n['name']}</option>\n";
}
?>
</select>
</div>
<div>

<?

	$date = $db->q("SELECT DISTINCT SUBSTRING(date,1,10) as date FROM game_log WHERE neighbourhood_id='{$q['community']}' AND game_id='{$q['game']}' AND resident_id='{$q['player']}' ");
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
		$archive = $db->q("SELECT * FROM game_log WHERE neighbourhood_id='{$q['community']}' AND game_id='{$q['game']}' AND resident_id='{$q['player']}' AND date>='$from' AND date<'$to'" );
		_debug($archive);
	}
?>


