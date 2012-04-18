<?php /* Smarty version 2.6.26, created on 2011-12-15 12:29:15
         compiled from page.detail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'fnRemoveElement', 'page.detail.tpl', 1, false),array('modifier', 'fnBuildRewriteUrl', 'page.detail.tpl', 4, false),array('modifier', 'unserialize', 'page.detail.tpl', 23, false),array('modifier', 'strrpos', 'page.detail.tpl', 25, false),array('modifier', 'lower', 'page.detail.tpl', 26, false),array('modifier', 'substr', 'page.detail.tpl', 26, false),array('modifier', 'replace', 'page.detail.tpl', 26, false),array('modifier', 'regex_replace', 'page.detail.tpl', 48, false),array('modifier', 'capitalize', 'page.detail.tpl', 48, false),array('modifier', 'upper', 'page.detail.tpl', 57, false),)), $this); ?>
<?php $this->assign('ary_valid_input', fnRemoveElement($this->_tpl_vars['ary_valid_input'], 'id')); ?>
<div id="profile_detail">
    <div class="nav-bar top">
        <a href="<?php echo $this->_tpl_vars['CFG']->www; ?>
page/<?php echo fnBuildRewriteUrl(fnRemoveElement($this->_tpl_vars['ary_valid_input'], 'winner')); ?>
" class="close top">CLOSE</a>
        <div class="clear"><br class="accessibility" /></div>
    </div>
    
    <script type="text/javascript">
	var addthis_url = wwwroot + 'detail/id/<?php echo $this->_tpl_vars['ary_profile']['id']; ?>
';
	//<?php echo '
	var addthis_share = { url: addthis_url }
	//'; ?>

	
	</script>
    <div class="addthis_toolbox addthis_default_style ">
        <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:url="<?php echo $this->_tpl_vars['CFG']->wwwroot; ?>
detail/id/<?php echo $this->_tpl_vars['ary_profile']['id']; ?>
"></a>
        <a class="addthis_button_tweet" twitter:url="<?php echo $this->_tpl_vars['CFG']->wwwroot; ?>
detail/id/<?php echo $this->_tpl_vars['ary_profile']['id']; ?>
"></a>
        <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:url="<?php echo $this->_tpl_vars['CFG']->wwwroot; ?>
detail/id/<?php echo $this->_tpl_vars['ary_profile']['id']; ?>
"></a>
        <a class="addthis_counter addthis_pill_style"></a>
    </div>
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ygaward"> </script>
    <div id="profile">
    <?php $this->assign('entry_data', unserialize($this->_tpl_vars['ary_profile']['entry_data'])); ?>
    <?php $_from = $this->_tpl_vars['entry_data']['file']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ary_file']):
?>
        <?php $this->assign('tmp_pos', strrpos($this->_tpl_vars['ary_file']['name'], '.')); ?>
        <?php $this->assign('file_type', ((is_array($_tmp=substr(((is_array($_tmp=$this->_tpl_vars['ary_file']['name'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)), $this->_tpl_vars['tmp_pos']))) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '') : smarty_modifier_replace($_tmp, '.', ''))); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'search.detail.tpl', 'smarty_include_vars' => array('ary_file' => $this->_tpl_vars['ary_file'],'file_type' => $this->_tpl_vars['file_type'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endforeach; endif; unset($_from); ?>
    <?php $_from = $this->_tpl_vars['entry_data']['url']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ary_file']):
?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'search.detail.tpl', 'smarty_include_vars' => array('ary_file' => $this->_tpl_vars['ary_file'],'file_type' => 'url')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endforeach; endif; unset($_from); ?>
    </div>
    <h2 class="member_list"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ary_profile']['user_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, ';', '<br/>') : smarty_modifier_replace($_tmp, ';', '<br/>')))) ? $this->_run_mod_handler('replace', true, $_tmp, '+', ' ') : smarty_modifier_replace($_tmp, '+', ' ')))) ? $this->_run_mod_handler('replace', true, $_tmp, '###', ';') : smarty_modifier_replace($_tmp, '###', ';')); ?>
</h2>
    <table class="desc" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <th>Name</th>
            <th><?php if ($this->_tpl_vars['entry_data']['school']): ?>School<?php else: ?>Agency<?php endif; ?></th>
        </tr>
        <tr>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['ary_profile']['entry_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, '+', ' ') : smarty_modifier_replace($_tmp, '+', ' ')); ?>
</td>
            <td><?php if ($this->_tpl_vars['entry_data']['school']): ?><?php echo $this->_tpl_vars['entry_data']['school']['name']; ?>
<?php else: ?><?php echo $this->_tpl_vars['entry_data']['import']['network']; ?>
<?php endif; ?></td>
        </tr>
        <tr>
            <th>Category</th>
            <th>Country</th>
        </tr>
        <tr>
            <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['entry_data']['import']['Category'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/^\([^\)]+\)/', '') : smarty_modifier_regex_replace($_tmp, '/^\([^\)]+\)/', '')))) ? $this->_run_mod_handler('replace', true, $_tmp, '_', ' ') : smarty_modifier_replace($_tmp, '_', ' ')))) ? $this->_run_mod_handler('replace', true, $_tmp, '+', ' ') : smarty_modifier_replace($_tmp, '+', ' ')))) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['entry_data']['import']['country'])) ? $this->_run_mod_handler('replace', true, $_tmp, '+', ' ') : smarty_modifier_replace($_tmp, '+', ' ')); ?>
</td>
        </tr>
        <tr>
            <th>Client</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <td><?php echo $this->_tpl_vars['entry_data']['client_name']; ?>
</td>
            <td><span class="award <?php echo ((is_array($_tmp=$this->_tpl_vars['ary_profile']['award'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['ary_profile']['award'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span></td>
        </tr>
    </table>
    <div class="clear"><br class="accessibility" /></div>
        <div class="nav-bar bottom">
        <a href="<?php echo $this->_tpl_vars['CFG']->www; ?>
page/<?php echo fnBuildRewriteUrl($this->_tpl_vars['ary_valid_input']); ?>
" class="close bottom">CLOSE</a>
        <div class="clear"><br class="accessibility" /></div>
    </div>
</div><!--<div id="profile_detail">-->   