<?php /* Smarty version 2.6.26, created on 2012-01-10 10:14:59
         compiled from main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'retrieveUserIp', 'main.tpl', 71, false),array('modifier', 'date_format', 'main.tpl', 72, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $this->_tpl_vars['CFG']->project; ?>
 - Admin</title>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['CFG']->www; ?>
css/admin/screen.css" media="screen" rel="stylesheet" />
<link media="screen" href="<?php echo $this->_tpl_vars['CFG']->www; ?>
css/admin/admin_style.css" type="text/css" rel="stylesheet"/>
<?php if ($this->_tpl_vars['CFG']->skin): ?><link media="screen" href="<?php echo $this->_tpl_vars['CFG']->www; ?>
css/admin/<?php echo $this->_tpl_vars['CFG']->skin; ?>
/admin_style.css" type="text/css" rel="stylesheet"/><?php endif; ?>
<!--[if IE]><style type="text/css" media="screen">@import "<?php echo $this->_tpl_vars['CFG']->www; ?>
css/admin/ie.css";</style><![endif]-->
<!--[if IE 7]><style type="text/css" media="screen">@import "<?php echo $this->_tpl_vars['CFG']->www; ?>
css/admin/ie7.css";</style><![endif]-->
<!--[if lt IE 7]> <style type="text/css" media="screen">@import "<?php echo $this->_tpl_vars['CFG']->www; ?>
css/admin/ie6.css";</style><![endif]-->
<!--[if gt IE 7]> <style type="text/css" media="screen">@import "<?php echo $this->_tpl_vars['CFG']->www; ?>
css/admin/ie8.css";</style><![endif]-->
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
lib/js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
js/common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
js/admin/admin_js.js"></script>
</head>
<body class="admin" id="body">
<div class="outter_container">
<div class="container">
	<div class="header">
		<div class="logo_bg_l">
			<div class="logo_bg_r">
				<h1 class="logo_bg"><strong class="accessibility"><?php echo $this->_tpl_vars['CFG']->project; ?>
 - Admin</strong><?php if ($this->_tpl_vars['USER']->username): ?><span><a href="admin_login.php?logout=logout"><q>Logout</q></a></span><?php endif; ?></h1>
			</div>
		</div>
		<?php if ($this->_tpl_vars['USER']->ACL->admin['view']): ?>
		<div class="nav">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "nav.tpl", 'smarty_include_vars' => array('section' => $this->_tpl_vars['section'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="body">
	<?php if ($this->_tpl_vars['page_file']): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['page_file'], 'smarty_include_vars' => array('page_content' => $this->_tpl_vars['page_content'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "home.tpl", 'smarty_include_vars' => array('page_content' => $this->_tpl_vars['page_content'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
	</div>
	<div class="footer">
		<div class="col_group">
			<?php if ($this->_tpl_vars['USER']->username): ?>
			<div class="col_3">
				<h3>Your Login Status</h3>
				<ul>
					<li>Your Current Login IP: <?php echo $this->_tpl_vars['USER']->currentip; ?>
</li>
					<li>Your Last Login Time: <?php echo $this->_tpl_vars['USER']->lastlogintime; ?>
</li>
					<li>Your Last Login IP: <?php echo $this->_tpl_vars['USER']->lastip; ?>
</li>
				</ul>
			</div>
			<div class="col_3">
				<h3 class="<?php if ($this->_tpl_vars['login_log']['today']['failed'] > 20): ?>warning<?php endif; ?>">Today Login Status</h3>
				<ul>
					<li>Attempt Login: <?php echo $this->_tpl_vars['login_log']['today']['attempt']; ?>
</li>
					<li>Successed Login: <?php echo $this->_tpl_vars['login_log']['today']['successed']; ?>
</li>
					<li>Failed Login: <?php echo $this->_tpl_vars['login_log']['today']['failed']; ?>
</li>
					<li>Failed Login Rate: <?php echo $this->_tpl_vars['login_log']['today']['failed_rate']; ?>
%</li>
				</ul>
			</div>
			<div class="col_3">
				<h3 class="<?php if ($this->_tpl_vars['login_log']['today']['failed'] > 20): ?>warning<?php endif; ?>">Yesterday Login Status</h3>
				<ul>
					<li>Attempt Login: <?php echo $this->_tpl_vars['login_log']['yesterday']['attempt']; ?>
</li>
					<li>Successed Login: <?php echo $this->_tpl_vars['login_log']['yesterday']['successed']; ?>
</li>
					<li>Failed Login: <?php echo $this->_tpl_vars['login_log']['yesterday']['failed']; ?>
</li>
					<li>Failed Login Rate: <?php echo $this->_tpl_vars['login_log']['yesterday']['failed_rate']; ?>
%</li>
				</ul>
			</div>
			<?php else: ?>
			<div class="col_1">
				<ul>
					<li>Your Current IP: <?php echo retrieveUserIp($this->_tpl_vars['dummy']); ?>
</li>
					<li>Current Time: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A, %B %e, %Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%A, %B %e, %Y %H:%M:%S")); ?>
</li>
				</ul>
			</div>
			<?php endif; ?>
			<div class="clear"><hr class="accessibility"/></div>
		</div>
		<p class="info"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
 <?php echo $this->_tpl_vars['CFG']->project; ?>
 &amp; We Are Reborn &copy;</p>
	</div>
</div>
</div>
<script language="javascript" type="text/javascript">
  <?php echo '
  fnFunctionSwitch();
  '; ?>

</script>
</body>
</html>