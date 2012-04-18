<?php /* Smarty version 2.6.26, created on 2012-01-10 10:15:13
         compiled from login_log.retrieveLoginLog.sql */ ?>
SELECT	date,
		attempt,
		successed,
		failed
FROM	login_log
WHERE	date = '<?php echo $this->_tpl_vars['date']; ?>
'