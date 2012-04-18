<?php /* Smarty version 2.6.26, created on 2012-01-10 10:15:14
         compiled from login_log.insertLoginLog.sql */ ?>
INSERT
INTO	login_log
SET		date		= '<?php echo $this->_tpl_vars['query']->date; ?>
',
		attempt		= 1,
		successed	= '<?php if ($this->_tpl_vars['query']->flag == 'successed'): ?>1<?php else: ?>0<?php endif; ?>',
		failed		= '<?php if ($this->_tpl_vars['query']->flag == 'failed'): ?>1<?php else: ?>0<?php endif; ?>'