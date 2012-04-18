<?php /* Smarty version 2.6.26, created on 2012-01-10 11:34:15
         compiled from /var/www/other/example_2012//admin/mod/project_sync/project_sync.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'is_array', '/var/www/other/example_2012//admin/mod/project_sync/project_sync.tpl', 27, false),)), $this); ?>

  <div class="custom_field" style="padding-bottom:20px">
    <div class="page_content" id="account_list_container">
    	<h2>Admin Project</h2>
    	<?php if ($this->_tpl_vars['page_content']['project_sync']['msg']): ?>
    	<p align="center"><?php echo $this->_tpl_vars['page_content']['project_sync']['msg']; ?>
</p>
    	<?php else: ?>
		<form class="normal_form" name="update_password" action="" method="POST" >
			<input type="hidden" name="section" id="section" value="<?php echo $this->_tpl_vars['CFG']->section; ?>
"/>
			<input type="hidden" name="mode" id="mode" value="update"/>
			<input type="hidden" name="save" id="save" value="1"/>
			<div id="account_div" class="content_list_view">
				<table width="100%" border="0">
					<tr <?php if ($this->_tpl_vars['CFG']->mod == 'LIVE'): ?>class="noDisplay"<?php endif; ?>>
						<th width="150"><label for="version">Install Script</label></th>
						<td colspan="3"><a href="?section=<?php echo $this->_tpl_vars['CFG']->section; ?>
&amp;mode=self_install" target="_blank">Click Here to download self install PHP</a></td>
					</tr>
					<tr <?php if ($this->_tpl_vars['CFG']->mod == 'DEV'): ?>class="noDisplay"<?php endif; ?>>
						<th width="150"><label for="version">Version</label></th>
						<td width="160"><input type="text" name="version" id="version" value="Latest"/></td>
                        <td width="100"><span>digits only</span></td>
						<td><input type="submit" name="save" id="save" value="Ver. Update"/></td>
					</tr>
					<tr>
                        <th width="150" valign="top"><label for="version">Log:</label></th>
						<td colspan="3"><div class="" style="height: 400px; overflow: auto;">
                        <?php if (is_array($this->_tpl_vars['page_content']['project_sync']['svn_log'])): ?>
                        <?php $_from = $this->_tpl_vars['page_content']['project_sync']['svn_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['log_row']):
?>
                        <p><?php echo $this->_tpl_vars['log_row']; ?>
</p>
                        <?php endforeach; endif; unset($_from); ?>
                        <?php else: ?>
                        <p>No Version yet</p>
                        <?php endif; ?>
                        </div></td>
					</tr>
				</table>
		  	</div>
		  	
	  	</form>
	  	
	  	<?php endif; ?>
 	</div>
  </div>