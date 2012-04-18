<?php

//print_r($_SESSION);

/*
  $CFG->google_code = 'UA-0000000-00';
  $CFG->facebook_api_id = '173314422723871';
  $CFG->facebook_api_key = '093bd9019160142919167811d5678678';
  $CFG->facebook_api_secret = 'da6e337a445ae7c4f15317edac31bef6';
  $CFG->facebook_api_url = 'http://apps.facebook.com/homesforhopes/';

  */

$fb = new Facebook(array(
'appId'=>$CFG->facebook_api_id,
'secret'=>$CFG->facebook_api_secret,
'cookie'=>true,
 ));
echo time()."<br/>\n<pre style='background:".xpAS::random_color(array('r'=>'31-9a',grey=>1))."'>\n";

 
$db = xpMysql::conn();




//$r = $db->table_list();

//for($i=0;$i<20000;$i++){
//    $arr=array('key'=>uniqid(),'TTL'=>time()+500,'value'=>$_SESSION);
////    $db->insert('xptesting',$arr);
//}

$data=$db->gets('xptesting','1','id'); 
// echo $tk = $fb->getAccessToken();
// print_r($fb->getUser());
$r=count($data);
_debug($r); 
 
// echo ("<br>https://graph.facebook.com/me/friends?access_token=".$fb->getAccessToken() );
//echo file_get_contents("https://graph.facebook.com/me/friends?access_token=".$fb->getAccessToken() );

//echo file_get_contents("https://graph.facebook.com/me/friends?access_token=".$fb->getAccessToken() );

//echo xplogmail::log('testing testing xplogmail');

function myMail($to, $subject, $message, $from){
    if(preg_match('/messagestudio\./i',$_SERVER['SERVER_NAME'])){
        $port =$port ? $port : 'http://rockquiz.messagestudio.ath.cx:82/sve.php';
//        $port =$port ? $port : _DOMAIN_ROOT_.'/amfXP/gateway.php';
        $arr['id'] = md5($port);
        $arr['from'] = $from;
        $arr['to'] = $to;
        $arr['subject']=$subject;
        $arr['body']= $message;
//        $arr['cmd']='testing';
//        $arr['what']='email';
        
        $data=base64_encode(serialize($arr));
        
        return file_get_contents( $port.'?cmd=testing&what=email&data='.$data);
                
    }else{
        return mail( $to, $from, $message, $header);
    }    
}


//echo $token =$fb->getAccessToken() ;
//
////$url = "http://graph.facebook.com/me/friends";
//$url = "https://66.220.146.47/me/friends";
//$buff = "access_token=".$token;
//$r = xpAS::curlOut("http://yahoo.com/");
//$url = 'www.yahoo.com';
//$r = file_get_contents($url);
//$r = facebook_local::download_pretending("https://graph.facebook.com/me/friends?access_token=".$fb->getAccessToken());

//print_r($_SESSION);

//echo $t = fb_rest::ask('auth.createToken');
//echo "\n";
//echo $ts = fb_rest::ask('auth.getSession',array('auth_token'=>$t));
//print_r(fb_rest::ask('users.getStandardInfo',array('uids'=>4,'fields'=>'name')));
//print_r(fb_rest::ask('friends.getLists'));

//print_r($r = $fb->api('/me/friends'));
//_debug(facebook_connector::friends('me'));
//print_r($fb->api('/me/likes'));

//_debug($fb->api('/fql.query',array('query'=>'SELECT name FROM user WHERE uid = me()')));
//_debug(facebook_connector::direct('/fql.query',array('query'=>'SELECT name FROM user WHERE uid = me()')));

//print_r($fb->api('/fql.query',array('query'=>'SELECT uid, name, pic_square FROM user WHERE uid = me() OR uid IN (SELECT uid2 FROM friend WHERE uid1 = me())')));
//print_r($fb->api('/fql.query',array('query'=>'SELECT flid, name FROM friendlist WHERE owner=601977521')));
//_debug($fb->api('/fql.query',array('query'=>'SELECT flid FROM friendlist WHERE owner=100000424391700')));

//print_r($fb->api('/me/feed'));
//print_r($fb->api('/KFCAustralia/feed'));
//$r = json_decode($fb->api('/me/feed'));
//$r = json_decode($fb->api('/me/feed'));
//_debug(facebook_connector::direct('/KFCAustralia/feed',array('fields'=>'id','limit'=>300,'until'=>'')),'data');
//_debug(facebook_connector::friends('me'));
//_debug(facebook_connector::feed('KFCAustralia'));
//_debug(facebook_connector::online_firends());

//$r = json_decode(file_get_contents('https://graph.facebook.com/100002042327888/feed'));

//_debug($r); 
//print_r($fb->api('/me/home'));
//print_r($fb->api('/me/movies'));
//print_r($fb->api('/me/notes'));
//print_r($fb->api('/me/permissions'));
//print_r($fb->api('/me/groups'));

//print_r(file_get_contents("https://api.facebook.com/method/users.getInfo?uids=4&fields=name&access_token=2227470867|2.Ot_h2wh0bwqNd5GxUPjq7Q__.3600.1305511200.0-100002042327888|cLdGj2CtS1P9EGeMyFXRrCvtecM&format=json"));

$msg =         array(
            'message'=>'Hello from API 1.21'.xpDate::today(),
            'name'=>'1st post from a1.21',
            'description'=>'test API posting function',
//        error with it    'privacy'=>'{"value": "NETWORKS_FRIENDS"}',//EVERYONE
            );
//_debug(facebook_connector::post('me',$msg));        
            

//_debug($fb->api('/614348712/feed','POST',
//        array(
//            'message'=>'Hello from API 1.21'.xpDate::today(),
//            'name'=>'1st post from a1.21',
//            'description'=>'test API posting function',
////            'privacy'=>'{"value": "NETWORKS_FRIENDS"}',//EVERYONE
//
//
//            )));
//



            
//create test user
//echo time()."\n";
//echo $r = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id={$CFG->facebook_api_id}&client_secret={$CFG->facebook_api_secret}&grant_type=client_credentials");
//echo "----\n";
//echo ("https://graph.facebook.com/{$CFG->facebook_api_id}/accounts/test-users?installed=true&permissions=read_stream&method=post&".$r ),"<br/>";
//for($i=0;$i<30;$i++)
//echo "\n".file_get_contents("https://graph.facebook.com/{$CFG->facebook_api_id}/accounts/test-users?installed=true&permissions=read_stream&method=post&".$r );
            
            
            
            
            
            
            
            
            
            
            

// 
////$r = $fb->api("/me/friends?access_token=".$fb->getAccessToken());
////$r = $fb->api("/me/friends?access_token=".$fb->getAccessToken());
//
//echo $r;
////
//    $this->setAppId($config['appId']);
//    $this->setApiSecret($config['secret']);
//    if (isset($config['cookie'])) {
//      $this->setCookieSupport($config['cookie']);
//    }
//    if (isset($config['domain'])) {
//      $this->setBaseDomain($config['domain']);
//    }
//    if (isset($config['fileUpload'])) {
//      $this->setFileUploadSupport($config['fileUpload']);
//    }