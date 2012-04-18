<?php
require_once($CFG->lib.'Smarty/Smarty.class.php');
class smarty_query extends Smarty {
    function smarty_query(){
        global $CFG;
        $this->template_dir    = $CFG->query;
        $this->compile_dir    = $CFG->tplcompile;
        parent::Smarty();
    }
}
?>