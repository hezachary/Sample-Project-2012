

<form action="" method="POST" >
	<input type="hidden" name="link" id="link" value="{$page_content.link|htmlentities}" />
	<div class="login_form">
		<p class="msg">{$page_content.msg}</p>
		<p class="row"><label for="username">User Name:</label> <input type="text" name="username" id="username" /></p>
		<p class="row"><label for="password">Password:</label> <input type="password" name="password" id="password" /></p>
		<p class="submit"><input type="submit" name="login" id="login" value="Login" /></p>
	</div>
</form>