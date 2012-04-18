<?php /* Smarty version 2.6.26, created on 2012-01-10 10:16:18
         compiled from nav.tpl */ ?>

    <ul>
      <li class="<?php if ($this->_tpl_vars['CFG']->section == ''): ?>selected<?php endif; ?>"><a href="?">Home</a></li>
      <li class="<?php if ($this->_tpl_vars['CFG']->section == 'register'): ?>selected<?php endif; ?>"><a href="?section=register">Maintain Register(s)</a></li>
	<?php $_from = $this->_tpl_vars['PAGE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['nav_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['section_item'] => $this->_tpl_vars['obj_setting']):
        $this->_foreach['nav_list']['iteration']++;
?>
      <li class="<?php if ($this->_tpl_vars['CFG']->section == $this->_tpl_vars['section_item']): ?>selected<?php endif; ?>"><a href="?section=<?php echo $this->_tpl_vars['section_item']; ?>
"><?php echo $this->_tpl_vars['obj_setting']->section_name; ?>
</a></li>
	<?php endforeach; endif; unset($_from); ?>
    </ul>