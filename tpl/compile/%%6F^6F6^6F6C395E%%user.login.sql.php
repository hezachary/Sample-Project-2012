<?php /* Smarty version 2.6.26, created on 2012-01-10 10:15:13
         compiled from user.login.sql */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mysql_escape_string', 'user.login.sql', 32, false),)), $this); ?>
SELECT	id,
		username,
		password,
		firstname,
		lastname,
		email,
		icq,
		skype,
		yahoo,
		aim,
		msn,
		phone1,
		phone2,
		department,
		address,
		city,
		country,
		lang,
		timezone,
		firstaccess,
		currentlogin,
		lastlogin,
		currentip,
		lastip,
		secret,
		picture,
		url,
		description,
		timemodified,
		role
FROM	`user`
WHERE	username = '<?php echo mysql_escape_string($this->_tpl_vars['username']); ?>
'
AND		password = '<?php echo mysql_escape_string($this->_tpl_vars['password']); ?>
'
LIMIT	1