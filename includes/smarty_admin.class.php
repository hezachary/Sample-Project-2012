<?php
require_once($CFG->lib.'Smarty/Smarty.class.php');
class smarty_admin extends Smarty {
    function smarty_admin(){
        global $CFG;
        $this->template_dir    = $CFG->backend;
        $this->compile_dir    = $CFG->tplcompile.'_admin';
        parent::Smarty();
    }
}
?>