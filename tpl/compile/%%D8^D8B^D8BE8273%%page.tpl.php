<?php /* Smarty version 2.6.26, created on 2012-01-12 16:52:20
         compiled from page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'page.tpl', 2, false),array('modifier', 'lower', 'page.tpl', 2, false),array('modifier', 'date', 'page.tpl', 30, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php $this->assign('section', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['page_file'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.tpl', '') : smarty_modifier_replace($_tmp, '.tpl', '')))) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_') : smarty_modifier_replace($_tmp, '.', '_')))) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp))); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title></title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="robots" content="INDEX,FOLLOW" />
        <link rel="icon" type="image/x-icon" href="<?php echo $this->_tpl_vars['CFG']->www; ?>
ico/site.ico" />
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->_tpl_vars['CFG']->www; ?>
ico/site.ico" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->_tpl_vars['CFG']->www; ?>
css/global.css" />               
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
lib/js/jquery.min.js"></script>
        <script type="text/javascript">
            // <![CDATA[
            var jq = jQuery.noConflict();
            // ]]>
        </script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
lib/js/jquery.uniform.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
lib/js/jquery.placehold.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
lib/js/jquery.json.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
lib/js/jquery.base64.min.js"></script>
        
        <script type="text/javascript">
            // <![CDATA[
            var wwwroot = "<?php echo $this->_tpl_vars['CFG']->wwwroot; ?>
";
            var www = "<?php echo $this->_tpl_vars['CFG']->www; ?>
";
            var gcode = "<?php echo $this->_tpl_vars['CFG']->google_code; ?>
";
            var time = new Date('<?php echo date('r'); ?>
');
            // ]]>
        </script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['CFG']->www; ?>
js/project.js"></script>
    </head>
    <body class="<?php echo $this->_tpl_vars['section']; ?>
">
        
        <div class="content">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['page_file'], 'smarty_include_vars' => array('page_content' => $this->_tpl_vars['page_content'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
        
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'widget.google_analytics.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    
    </body>
</html>