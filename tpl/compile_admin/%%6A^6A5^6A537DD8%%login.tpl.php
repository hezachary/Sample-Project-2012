<?php /* Smarty version 2.6.26, created on 2012-01-10 10:05:35
         compiled from login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlentities', 'login.tpl', 4, false),)), $this); ?>


<form action="" method="POST" >
	<input type="hidden" name="link" id="link" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['page_content']['link'])) ? $this->_run_mod_handler('htmlentities', true, $_tmp) : htmlentities($_tmp)); ?>
" />
	<div class="login_form">
		<p class="msg"><?php echo $this->_tpl_vars['page_content']['msg']; ?>
</p>
		<p class="row"><label for="username">User Name:</label> <input type="text" name="username" id="username" /></p>
		<p class="row"><label for="password">Password:</label> <input type="password" name="password" id="password" /></p>
		<p class="submit"><input type="submit" name="login" id="login" value="Login" /></p>
	</div>
</form>