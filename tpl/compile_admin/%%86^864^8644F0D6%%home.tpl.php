<?php /* Smarty version 2.6.26, created on 2012-01-10 10:15:14
         compiled from home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'home.tpl', 19, false),array('modifier', 'strtotime', 'home.tpl', 20, false),array('modifier', 'date', 'home.tpl', 23, false),array('modifier', 'mergeString', 'home.tpl', 23, false),array('modifier', 'replace', 'home.tpl', 29, false),array('modifier', 'capitalize', 'home.tpl', 29, false),)), $this); ?>
<?php if ($this->_tpl_vars['USER']->username): ?><?php endif; ?>
<div class="content custom_field">
    <div class="page_content">
        <h2>General Info<a id="page_detail" name="page_detail"></a></h2>
        <table class="image_list" cellpadding="2" cellspacing="2" border="0">
			<thead>
        	<tr>
				<th>Today Register</th>
				<th>Yesterday Register</th>
				<th>Last 7 Days Register</th>
				<th>Last 30 Days Register</th>
				<th>Last Month Register</th>
				<th>Total Register</th>
			</tr>
			</thead>
			<tbody>
        	<tr>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data=<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
~<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
&amp;search_field=timestamp&amp;search=Search"><?php echo $this->_tpl_vars['page_content']['today_reg']; ?>
</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data=<?php echo ((is_array($_tmp=strtotime('-1 day'))) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
&amp;search_field=timestamp&amp;search=Search"><?php echo $this->_tpl_vars['page_content']['last_1_day_reg']; ?>
</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data=<?php echo ((is_array($_tmp=strtotime('-7 day'))) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
~&amp;search_field=timestamp&amp;search=Search"><?php echo $this->_tpl_vars['page_content']['last_7_days_reg']; ?>
</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data=<?php echo ((is_array($_tmp=strtotime('-1 month'))) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
~&amp;search_field=timestamp&amp;search=Search"><?php echo $this->_tpl_vars['page_content']['last_30_days_reg']; ?>
</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data=<?php echo ((is_array($_tmp=strtotime('-1 month'))) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m') : smarty_modifier_date_format($_tmp, '%Y-%m')); ?>
-01~<?php echo ((is_array($_tmp=strtotime(mergeString(date('Y-m'), '-1 -1 day')))) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
&amp;search_field=timestamp&amp;search=Search"><?php echo $this->_tpl_vars['page_content']['last_month_reg']; ?>
</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp"><?php echo $this->_tpl_vars['page_content']['total_reg']; ?>
</a></td>
			</tr>
			</tbody>
		</table>
        <?php $_from = $this->_tpl_vars['page_content']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list_title_name'] => $this->_tpl_vars['ary_register_list']):
?>
        <h2><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list_title_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, '_', ' ') : smarty_modifier_replace($_tmp, '_', ' ')))) ? $this->_run_mod_handler('capitalize', true, $_tmp, true) : smarty_modifier_capitalize($_tmp, true)); ?>
<a id="page_detail" name="page_detail"></a> <a href="javascript:fnReloadField('<?php echo $this->_tpl_vars['list_title_name']; ?>
');">[Refresh]</a></h2>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'home_field.tpl', 'smarty_include_vars' => array('ary_register_list' => $this->_tpl_vars['ary_register_list'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endforeach; endif; unset($_from); ?>
    </div>
</div>