<?php
require_once($CFG->lib.'Smarty/Smarty.class.php');
class smarty_string extends Smarty {
    function smarty_string(){
        global $CFG;
        $this->register_resource("string", array("string_get_template", "string_get_timestamp", "string_get_secure", "string_get_trusted"));
        $this->compile_dir    = $CFG->tplcompile;
        parent::Smarty();
    }
    function fetch($string){
        return parent::fetch('string:'.$string);
    }
}
function string_get_template ($tpl_name, &$tpl_source, &$smarty_obj) {
    $tpl_source = $tpl_name;
    return true;
}
function string_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj) {
    // do database call here to populate $tpl_timestamp.
    $tpl_timestamp = time();
    return true;
}
function string_get_secure($tpl_name, &$smarty_obj) {
    // assume all templates are secure
    return true;
}
function string_get_trusted($tpl_name, &$smarty_obj) {
    // not used for templates
}
?>