<?php
//################################################################################
//start definition
//################################################################################
$CFG->tpl          = $CFG->dirroot.'tpl/';
$CFG->tplcompile= $CFG->dirroot.'tpl/compile';
$CFG->query      = $CFG->dirroot.'tpl/query';
$CFG->frontend     = $CFG->dirroot.'tpl/front_end';
$CFG->backend     = $CFG->dirroot.'tpl/admin';
$CFG->includes  = $CFG->dirroot.'includes/';
$CFG->lib  = $CFG->dirroot.'lib/';
$CFG->data        = $CFG->dirroot.'data/';
$CFG->www        = '/';
$CFG->cache     = $CFG->dirroot.'data/cache';
$CFG->skin        = 'sample';
$CFG->project    = 'example_2012';

$CFG->is_ssl_page   = false;

//set_include_path($CFG->includes);
set_include_path($CFG->lib);

mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
set_time_limit(3600);

if(@file_exists('/var/www/server_var/example_2012.php')){
    //Dev
    $CFG->debug        = 'ALL';
    $CFG->admin_email = 'hezachary@hotmail.com';
    require_once('/var/www/server_var/example_2012.php');
    $CFG->mod_path    = $CFG->dirroot.'/';
    $CFG->admin_email = array('Zac He'=>'sample@sample.com.au');
    error_reporting(E_ALL ^ E_NOTICE);
    $CFG->mod        = 'DEV';
    $CFG->emltrk    = 'http://www.sample.com/';
    $CFG->utm       = 'utm_source=referral&utm_medium=email&utm_campaign=sample';
    $CFG->ssl_page_list = array();
    $CFG->map_image = $CFG->www.'images/map';
    $CFG->holding     = false; // holding page switch
}else{
    //Live
    $CFG->wwwroot   = 'http://www.sample.com/';
    $CFG->www        = '/';
    $CFG->dbType    = 'PDO_MYSQL';
    $CFG->db        = array ('host' => 'localhost', 'username' => '', 'password' => '', 'dbname' => '');
    $CFG->debug        = 'All';//None
    error_reporting(E_ALL ^ E_NOTICE); 
    $CFG->AppId        = '';//
    $CFG->mod_path    = $CFG->dirroot;
    $CFG->admin_email = array('Zac He'=>'sample@sample.com.au');
    $CFG->mailbase    = '@'.str_replace(array('http:', 'www.','/'), '', $_SERVER['HTTP_HOST']);
    $CFG->mod        = 'LIVE';
    $CFG->emltrk    = '';
    $CFG->utm       = 'utm_source=referral&utm_medium=email&utm_campaign=sample';
    $CFG->google_code = 'UA-000000-00';//Google Analytics Code
    $CFG->facebook_api_id = '';
    $CFG->facebook_api_secret = '';
    $CFG->facebook_app_url = '';
    $CFG->ssl_page_list = array();
    $CFG->holding     = true; // holding page switch
}

function class_autoload($classname){
    global $CFG;
    $aryClassName = explode('_', $classname);
    switch($aryClassName[0]){
        case 'Zend':
            require_once($CFG->lib.'Zend/Loader.php');
            Zend_Loader::loadClass($classname, $CFG->lib);
            break;
        case 'Smarty':
            require_once($CFG->lib.'Smarty/Smarty.class.php');
            break;
        case 'Facebook':
            require_once($CFG->lib.'Facebook/facebook.php');
            break;
        case 'db':
            require_once($CFG->includes.'db_classes/'.$classname.'.class.php');
            break;
        default:
            if(file_exists($CFG->includes.$classname.'.class.php'))
                require_once($CFG->includes.$classname.'.class.php');
            else    
                require_once($CFG->includes.'classes/'.$classname.'.class.php');
            break;
    }
}
spl_autoload_register('class_autoload');
require_once($CFG->includes.'function.lib.php');
//fnCheckSSL();
//input extra mod settings
$PAGE = array();
fnInputConfig($CFG->mod_path.'admin', $PAGE);

if($_COOKIE['_ERR_']) error_reporting(E_ALL);