<?php


?>
<style>
h1{
float:left;
}
h5, input, p{
float:left;
margin-left:80px;
}

</style>

<h1>Inspection</h1>
<p>
	<a href="javascript:void(0)" onclick=" document.getElementById('FM').src='log_inspector.php'">Log</a>
</p>
<p>
	<a href="javascript:void(0)" onclick=" document.getElementById('FM').src='archive_inspector.php'">The archive</a>
</p>
<p>
	<a href="javascript:void(0)" onclick=" document.getElementById('FM').src='game_log_inspector.php'">The game log</a>
</p>
<p>
	<a href="javascript:void(0)" onclick=" document.getElementById('FM').src='message_inspector.php'">The message box</a>
</p>
<iframe id="FM" width="100%" height="96%"></iframe>