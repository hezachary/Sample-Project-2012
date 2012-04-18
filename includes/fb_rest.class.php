<?php

//old facebook REST api

class fb_rest{
    
    static $auth_token;        // application auth token    
    static $access_token;        // user auth token
    static $stored_token_path = 'SignedRequest,oauth_token';
    
    static function api_info($key='null'){
        global $CFG;
        $api = array(
            'id'=>$CFG->facebook_api_id,
              'key'=>$CFG->facebook_api_key,
            'secret'=>$CFG->facebook_api_secret,
            'url'=>$CFG->facebook_api_url,
            );
    
        return /*$key ? $api[$key] : */$api;    
    }
    
    /**
     * api request
     *
     * @param string     $method     method name
     * @param mix         $buff        string or keyed array    
     * @param string     $token         access token
     * @return string
     */
    function ask($method, $buff=''/*array()*/, $format='json'){
        $token  = self::token($token);
        $q = is_string($buff) ? $buff : xpAS::compound($buff);
        
        if(!self::$auth_token) self::oauth_token();
        $api = self::api_info();
        $q .= '&access_token='.self::$auth_token;
        $q .= '&format='.$format;
        $q .= '&app_id='.$api['id'];
        $q .= '&app_key='.$api['key'];
        $q .= '&app_secret='.$api['secret'];
        $q = "https://api.facebook.com/method/$method?$q";
    
        return     file_get_contents($q);
        
    }

    /**
     * get or retrieve token
     *
     * @param string $token
     * @return string 
     */
    function token($token=null){
        if($token) 
            return self::$access_token = $token;
        if(! self::$access_token ) 
            self::$access_token=xpAS::get($_SESSION,self::$stored_token_path) ;
        return self::$access_token ;
    }
    
    function oauth_token(){
        $api = self::api_info();
        $r = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id={$api['id']}&client_secret={$api['secret']}&grant_type=client_credentials");
        return self::$auth_token = xpAS::get(explode('=',$r),1);    
    }
    
    
}