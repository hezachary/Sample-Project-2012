<?php
class google_analytics{
    public static function set($data = null){
        global $CFG;
        $data = $data == null?$_REQUEST:$data;
        $_SESSION['utm']['utm_source'] = isset($_SESSION['utm']['utm_source'])?$_SESSION['utm']['utm_source']:'';
        $_SESSION['utm']['utm_medium'] = isset($_SESSION['utm']['utm_medium'])?$_SESSION['utm']['utm_medium']:'';
        $_SESSION['utm']['utm_campaign'] = isset($_SESSION['utm']['utm_campaign'])?$_SESSION['utm']['utm_campaign']:'';
        if($data['utm_source']){
            $_SESSION['utm']['utm_source'] = $data['utm_source'];
        }
        if($data['utm_medium']){
            $_SESSION['utm']['utm_medium'] = $data['utm_medium'];
        }
        if($data['utm_campaign']){
            $_SESSION['utm']['utm_campaign'] = $data['utm_campaign'];
        }
        $CFG->utm_source = $_SESSION['utm']['utm_source'];
        $CFG->utm_medium = $_SESSION['utm']['utm_medium'];
        $CFG->utm_campaign = $_SESSION['utm']['utm_campaign'];
    }

}