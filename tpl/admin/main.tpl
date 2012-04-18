<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{$CFG->project} - Admin</title>
<link rel="stylesheet" type="text/css" href="{$CFG->www}css/admin/screen.css" media="screen" rel="stylesheet" />
<link media="screen" href="{$CFG->www}css/admin/admin_style.css" type="text/css" rel="stylesheet"/>
{if $CFG->skin}<link media="screen" href="{$CFG->www}css/admin/{$CFG->skin}/admin_style.css" type="text/css" rel="stylesheet"/>{/if}
<!--[if IE]><style type="text/css" media="screen">@import "{$CFG->www}css/admin/ie.css";</style><![endif]-->
<!--[if IE 7]><style type="text/css" media="screen">@import "{$CFG->www}css/admin/ie7.css";</style><![endif]-->
<!--[if lt IE 7]> <style type="text/css" media="screen">@import "{$CFG->www}css/admin/ie6.css";</style><![endif]-->
<!--[if gt IE 7]> <style type="text/css" media="screen">@import "{$CFG->www}css/admin/ie8.css";</style><![endif]-->
<script language="javascript" type="text/javascript" src="{$CFG->www}lib/js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="{$CFG->www}js/common.js"></script>
<script language="javascript" type="text/javascript" src="{$CFG->www}js/admin/admin_js.js"></script>
</head>
<body class="admin" id="body">
<div class="outter_container">
<div class="container">
	<div class="header">
		<div class="logo_bg_l">
			<div class="logo_bg_r">
				<h1 class="logo_bg"><strong class="accessibility">{$CFG->project} - Admin</strong>{if  $USER->username}<span><a href="admin_login.php?logout=logout"><q>Logout</q></a></span>{/if}</h1>
			</div>
		</div>
		{if  $USER->ACL->admin.view}
		<div class="nav">
			{include file=nav.tpl section=$section}
		</div>
		{/if}
	</div>
	<div class="body">
	{if $page_file}
		{include file=$page_file page_content=$page_content}
	{else}
		{include file=home.tpl page_content=$page_content}
	{/if}
	</div>
	<div class="footer">
		<div class="col_group">
			{if  $USER->username}
			<div class="col_3">
				<h3>Your Login Status</h3>
				<ul>
					<li>Your Current Login IP: {$USER->currentip}</li>
					<li>Your Last Login Time: {$USER->lastlogintime}</li>
					<li>Your Last Login IP: {$USER->lastip}</li>
				</ul>
			</div>
			<div class="col_3">
				<h3 class="{if $login_log.today.failed > 20}warning{/if}">Today Login Status</h3>
				<ul>
					<li>Attempt Login: {$login_log.today.attempt}</li>
					<li>Successed Login: {$login_log.today.successed}</li>
					<li>Failed Login: {$login_log.today.failed}</li>
					<li>Failed Login Rate: {$login_log.today.failed_rate}%</li>
				</ul>
			</div>
			<div class="col_3">
				<h3 class="{if $login_log.today.failed > 20}warning{/if}">Yesterday Login Status</h3>
				<ul>
					<li>Attempt Login: {$login_log.yesterday.attempt}</li>
					<li>Successed Login: {$login_log.yesterday.successed}</li>
					<li>Failed Login: {$login_log.yesterday.failed}</li>
					<li>Failed Login Rate: {$login_log.yesterday.failed_rate}%</li>
				</ul>
			</div>
			{else}
			<div class="col_1">
				<ul>
					<li>Your Current IP: {$dummy|@retrieveUserIp}</li>
					<li>Current Time: {$smarty.now|date_format:"%A, %B %e, %Y %H:%M:%S"}</li>
				</ul>
			</div>
			{/if}
			<div class="clear"><hr class="accessibility"/></div>
		</div>
		<p class="info">{$smarty.now|date_format:"%Y"} {$CFG->project} &amp; We Are Reborn &copy;</p>
	</div>
</div>
</div>
<script language="javascript" type="text/javascript">
  {literal}
  fnFunctionSwitch();
  {/literal}
</script>
</body>
</html>