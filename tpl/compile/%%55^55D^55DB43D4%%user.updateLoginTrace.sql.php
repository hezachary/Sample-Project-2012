<?php /* Smarty version 2.6.26, created on 2012-01-10 10:15:13
         compiled from user.updateLoginTrace.sql */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mysql_escape_string', 'user.updateLoginTrace.sql', 2, false),)), $this); ?>
UPDATE	`user`
SET		currentlogin= '<?php echo mysql_escape_string($this->_tpl_vars['currentlogin']); ?>
',
		lastlogin	= '<?php echo mysql_escape_string($this->_tpl_vars['lastlogin']); ?>
',
		currentip	= '<?php echo mysql_escape_string($this->_tpl_vars['currentip']); ?>
',
		lastip		= '<?php echo mysql_escape_string($this->_tpl_vars['lastip']); ?>
'
WHERE	id			= '<?php echo mysql_escape_string($this->_tpl_vars['id']); ?>
'