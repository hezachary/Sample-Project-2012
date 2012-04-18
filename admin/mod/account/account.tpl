
  <div class="custom_field">
    <div class="page_content" id="account_list_container">
    	<h2>Admin Account</h2>
    	{if $page_content.account.msg}
    	<p align="center">{$page_content.account.msg}</p>
    	{else}
		<form class="normal_form" name="update_password" action="" method="POST" >
			<input type="hidden" name="section" id="section" value="{$CFG->section}"/>
			<div id="account_div" class="content_list_view">
				<table width="100%" border="0">
					<tr>
					{if $page_content.account.validation.password}
						<th><span>{$page_content.account.validation.password}</span></th>
					</tr>
					{else}
					<tr>
						<th width="150"><lable for="old_password">Current Password</lable></th>
						<td width="160"><input type="password" name="old_password" id="old_password"/></td>
						<td><span>{$page_content.account.validation.old_password}</span></td>
					</tr>
					
					<tr>
						<th><lable for="new_password">New Password</lable></th>
						<td><input type="password" name="new_password" id="new_password"/></td>
						<td><span>{$page_content.account.validation.new_password}</span></td>
					</tr>
					
					<tr>
						<th><lable for="con_password">Re-type Password</lable></th>
						<td><input type="password" name="con_password" id="con_password"/></td>
						<td><span>{$page_content.account.validation.con_password}</span></td>
					</tr>
					
					<tr>
						<th><lable for="sug_password">Suggest Password</lable></th>
						<td width="700"><input type="text" name="sug_password" id="sug_password" style="width: 300px;" readonly="readonly" /><input type="button" value="Populate a New Password"  onclick="randmoPassword()"/></td>
					</tr>
					{/if}
				</table>
		  	</div>
		  	<p class="submit" align="right"><input type="submit" name="save" id="save" value="Update"/></p>
		  	
	  	</form>
	  	
	  	{/if}
 	</div>
  </div>
  <script type="text/javascript">
  var www = '{$CFG->www}';
  {literal}
	function randmoPassword(){
		var offset = 31;
		var range = 126 - offset;
		var pw = '';
		for(var i = 0; i < 32; i++){
			pw += String.fromCharCode((offset + Math.floor(Math.random()*range)));
		}
		$("#sug_password").val(pw);
	}
	randmoPassword();
  {/literal}
  </script>