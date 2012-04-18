

<form action="" method="POST" >
	<div class="login_form">
		<p class="msg">{$msg}</p>
		<p class="row"><label for="username">ID:</label> <input type="text" name="username" id="username" /></p>
		<p class="row"><label for="password">PW:</label> <input type="password" name="password" id="password" /></p>
		<p class="row"><label for="orderby">Order By:</label> <input type="text" name="orderby" id="orderby" /></p>
		<p>Sample: {$order_by_sample}</p>
		<p class="submit"><input type="submit" name="login" id="login" value="Retrive CSV" /></p>
	</div>
</form>