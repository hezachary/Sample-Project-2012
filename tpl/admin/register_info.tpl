<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Colgate Bright Smiles - Admin</title>
<link rel="stylesheet" type="text/css" href="{$CFG->www}css/screen.css" media="screen" rel="stylesheet" />
<link media="screen" href="{$CFG->www}css/admin_style.css" type="text/css" rel="stylesheet"/>
<!--[if IE]><style type="text/css" media="screen">@import "{$CFG->www}css/ie.css";</style><![endif]-->
<!--[if IE 7]><style type="text/css" media="screen">@import "{$CFG->www}css/ie7.css";</style><![endif]-->
<!--[if lt IE 7]> <style type="text/css" media="screen">@import "{$CFG->www}css/ie6.css";</style><![endif]-->
<!--[if gt IE 7]> <style type="text/css" media="screen">@import "{$CFG->www}css/ie8.css";</style><![endif]-->
<script language="javascript" type="text/javascript" src="{$CFG->www}js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="{$CFG->www}js/common.js"></script>
<script language="javascript" type="text/javascript" src="{$CFG->www}js/admin_js.js"></script>
</head>
<body class="admin info" id="body">
<div class="">
	<h1 align="center">Register Info</h1>
	<table class="info" width="100%" cellpadding="2" cellspacing="2" border="2" id="register_{$register_content->retrieveId()}">
		<tr>
			<th width="200">Full Name</th>
			<td colspan="3">{$register_content->fullname}</td>
		</tr>
		<tr>
			<th>Email</th>
			<td>{$register_content->email}</td>
			<th width="100">Phone</th>
			<td>{$register_content->phone}</td>
		</tr>
		<tr>
			<th>Address</th>
			<td colspan="3">{$register_content->address}</td>
		</tr>
		<tr>
			<th>Woolworths Receipt Number</th>
			<td>{$register_content->woolworths_receipt_number}</td>
			<th>Date Purchased</th>
			<td>{$register_content->date_purchased}</td>
		</tr>
		<tr>
			<th>Private Id</td>
			<td>{$register_content->clientId}</td>
			<th>Mail Send</td>
			<td>
				{if $image_content.approved == 'approved'}
					<form name="resend_email" action="" method="post">
						<input type="hidden" name="section" value="register_detail"/>
						<input type="hidden" name="id" value="{$video_content.id}"/>
						<div>
							<span>{if $register_content->mailsend}{$register_content->mailsend}{else}N/A{/if}</span>
							<input type="submit" name="resend_email" value="Manually Send Email"/>
						</div>
					</form>
				{else}
					N/A
				{/if}
			</td>
		</tr>
		<tr>
			<td colspan="4" valign="middle" align="center"><a href="{$CFG->www}image.php?id={$image_content.id}&amp;type=org" target="_blank"><img src="{$CFG->www}image.php?id={$image_content.id}&amp;type=big"/></a></td>
		</tr>
	</table>
</div>
</body>
</html>