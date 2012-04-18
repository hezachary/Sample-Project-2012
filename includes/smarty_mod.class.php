<?php
require_once($CFG->lib.'Smarty/Smarty.class.php');
class smarty_mod extends Smarty {
    function smarty_mod(){
        global $CFG;
        $this->template_dir    = $CFG->dirroot.'www/admin/mod/'.$CFG->section;
        $this->compile_dir    = $CFG->tplcompile;
        parent::Smarty();
    }
}
?>