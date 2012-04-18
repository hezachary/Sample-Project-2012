<?php /* Smarty version 2.6.26, created on 2012-01-10 11:36:47
         compiled from install.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'addslashes', 'install.tpl', 23, false),)), $this); ?>
<?php echo '<?php'; ?>

define('ABSPATH', dirname(__FILE__).'/');
if(file_exists(ABSPATH.'index.php')):
    echo 'Project already deploied, install cannot run twice unless you remove the index.php and backup all your data.';
    exit();
endif;


$out = array();
exec('svn checkout <?php echo $this->_tpl_vars['CFG']->project_sync->svn['url']; ?>
 '.ABSPATH.' --username <?php echo $this->_tpl_vars['CFG']->project_sync->svn['id']; ?>
 --password <?php echo $this->_tpl_vars['CFG']->project_sync->svn['pw']; ?>
 --no-auth-cache', $out);
echo '<pre>';
print_r($out);
flush();

/*
<?php $_from = $this->_tpl_vars['CFG']->project_sync->svn['folder_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['folder'] => $this->_tpl_vars['access']):
?>
*/

$target_folder = ABSPATH.'<?php echo $this->_tpl_vars['folder']; ?>
';
@mkdir($target_folder);
chmod($target_folder, '<?php echo $this->_tpl_vars['access']['chmod']; ?>
');
file_put_contents($target_folder.'.htaccess', '<?php echo addslashes($this->_tpl_vars['access']['htaccess']); ?>
');

/*
<?php endforeach; endif; unset($_from); ?>
*/
<?php echo '?>'; ?>