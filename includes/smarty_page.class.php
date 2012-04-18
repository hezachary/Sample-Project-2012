<?php
require_once($CFG->lib.'Smarty/Smarty.class.php');
class smarty_page extends Smarty {
    function smarty_page(){
        global $CFG;
        $this->template_dir    = $CFG->frontend;
        $this->compile_dir    = $CFG->tplcompile;
        parent::Smarty();
    }
}
?>