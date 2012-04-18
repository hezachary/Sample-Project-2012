
  <div class="custom_field" style="padding-bottom:20px">
    <div class="page_content" id="account_list_container">
    	<h2>Admin Project</h2>
    	{if $page_content.project_sync.msg}
    	<p align="center">{$page_content.project_sync.msg}</p>
    	{else}
		<form class="normal_form" name="update_password" action="" method="POST" >
			<input type="hidden" name="section" id="section" value="{$CFG->section}"/>
			<input type="hidden" name="mode" id="mode" value="update"/>
			<input type="hidden" name="save" id="save" value="1"/>
			<div id="account_div" class="content_list_view">
				<table width="100%" border="0">
					<tr {if $CFG->mod == 'LIVE'}class="noDisplay"{/if}>
						<th width="150"><label for="version">Install Script</label></th>
						<td colspan="3"><a href="?section={$CFG->section}&amp;mode=self_install" target="_blank">Click Here to download self install PHP</a></td>
					</tr>
					<tr {if $CFG->mod == 'DEV'}class="noDisplay"{/if}>
						<th width="150"><label for="version">Version</label></th>
						<td width="160"><input type="text" name="version" id="version" value="Latest"/></td>
                        <td width="100"><span>digits only</span></td>
						<td><input type="submit" name="save" id="save" value="Ver. Update"/></td>
					</tr>
					<tr>
                        <th width="150" valign="top"><label for="version">Log:</label></th>
						<td colspan="3"><div class="" style="height: 400px; overflow: auto;">
                        {if $page_content.project_sync.svn_log|@is_array}
                        {foreach from=$page_content.project_sync.svn_log item='log_row'}
                        <p>{$log_row}</p>
                        {/foreach}
                        {else}
                        <p>No Version yet</p>
                        {/if}
                        </div></td>
					</tr>
				</table>
		  	</div>
		  	
	  	</form>
	  	
	  	{/if}
 	</div>
  </div>