<?php

/**
 * @author    : peter<stone256@hotmail.com
 * @name     : facebook_connector
 * @uses     : faceboo api wraper, to carry daily call
 */


class facebook_connector {
    
    static $facebook;
    static $info;
/**    
    function email(){
//method/notifications.sendEmail?
//recipients=564777744&
//subject=test&
//text=this%20is%20test&
//fbml=%3Cb%3Etest%20%3C%2Fb%3E&        

        $message = array(
            'recipients'=>'564777744',
            'subject'=>'test',
            'text'=>'this is test',
            'fbml'=>'<b>test</b>'        
        );

        $r =  self::Q("/notifications.sendEmail",$message);
        return  $r . ' - ' .self::_facebook()->api("/method/notifications.sendEmail",'POST',$message);

    }
*/
    
    function my_info(){
        return self::$info = self::Q("/me");
    }

    function friends($who='me',$limit=100000 ){
        return self::Q("/$who/friends",array('fields'=>'id,name,email,first_name,last_name',),$limit);
//        return self::_facebook()->api("/$who/friends",array('fields'=>'id','limit'=>$limit,'until'=>''));
    }
    
    
    function feed($who,$limit=100000){
        return self::Q("/$who/feed",array('fields'=>'id'),$limit);
    }

//    function post($who,$message=array()){
//        return self::_facebook()->api("/$who/feed",'POST',$message);
//    }    
    
    function post($who,$message=array()){
        return self::_facebook()->api("/$who/feed",'POST',$message);
        
//print_r(/*$fb->api*/array ('/614348712/feed','POST',
//        array(
//            'message'=>'Hello from API 1.21'.xpDate::today(),
//            'name'=>'1st post from a1.21',
//            'description'=>'test API posting function',
//            'privacy'=>'{"value": "NETWORKS_FRIENDS"}',//EVERYONE
//
/*    privacy:        A JSON-encoded object that defines the privacy setting for a post, video, or album. It contains the following fields.

    * value (string): The privacy value for the object, specify one of EVERYONE, CUSTOM, ALL_FRIENDS, NETWORKS_FRIENDS, FRIENDS_OF_FRIENDS, SELF.
    * friends (string): For CUSTOM settings, this indicates which users can see the object. Can be one of EVERYONE, NETWORKS_FRIENDS (when the object can be seen by networks and friends), FRIENDS_OF_FRIENDS, ALL_FRIENDS, SOME_FRIENDS, SELF, or NO_FRIENDS (when the object can be seen by a network only).
    * networks (string): For CUSTOM settings, specify a comma-separated list of network IDs that can see the object, or 1 for all of a user's networks.
    * allow (string): When friends is set to SOME_FRIENDS, specify a comma-separated list of user IDs and friend list IDs that ''can'' see the post.
    * deny (string): When friends is set to SOME_FRIENDS, specify a comma-separated list of user IDs and friend list IDs that ''cannot'' see the post.

Only the user can specify the privacy settings for the post. You can create an interface that lets the user specify the privacy setting. For CUSTOM settings, use friends.get and friends.getLists to get the user's friends and friend lists to populate the interface, then pass along the selections to the privacy object.

Note: This privacy setting only applies to posts to the current or specified user's own Wall; Facebook ignores this setting for targeted Wall posts (when the user is writing on the Wall of a friend, Page, event, group connected to the user). Consistent with behavior on Facebook, all targeted posts are viewable by anyone who can see the target's Wall.

Privacy Policy: Any non-default privacy setting must be intentionally chosen by the user. You may not set a custom privacy setting unless the user has proactively specified that they want this non-default setting.


Indexable    Name    Type    Description
*    object_id    string    

The ID of an object on Facebook. This can be a video, note, link, photo, or photo album.
    id    string    

The object ID (this is an alias for the "object_id" column).
    value     string    

The privacy value for the object, one of EVERYONE, CUSTOM, ALL_FRIENDS, NETWORKS_FRIENDS, FRIENDS_OF_FRIENDS.
    description    string    

A description of the privacy settings. For CUSTOM settings, it can contain names of users, networks, and friend lists.
    allow    string    

The IDs of the specific users or friend lists that can see the object (as a comma-separated string).
    deny    string    

The IDs of the specific users or friend lists that cannot see the object (as a comma-separated string).
    owner_id    int    

The ID of the user who owns the object.
    networks    int    

The ID of the network that can see the object, or 1 for all of the user's networks.
    friends    string    

Which users can see the object. Can be one of EVERYONE, NETWORKS_FRIENDS, FRIENDS_OF_FRIENDS, ALL_FRIENDS, SOME_FRIENDS, SELF, or NO_FRIENDS.

*/
//            )));


                    
    }
    
    
    function online_friends(){

        return 
            self::Q('/method/fql.query',array("query"=>urlencode("SELECT uid, name FROM user WHERE uid  me()")));
    //        self::_facebook()->api('/fql.query',array("query"=>urlencode("SELECT uid, nameuid, name, email, pic_square FROM user WHERE uid = me() OR uid IN (SELECT uid2 FROM friend WHERE uid1 = me() )")));
    //        self::_facebook()->api('/fql.query',array("query"=>urlencode("SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = '100002042327888') and is_app_user ")));
    }
    
    
    function Q($method,$qs=array(),$limit=200){
        $paging=500;
        if(!(int)$limit) $limit=$paging;
        $qs['limit'] = $limit;

        if($limit<=$paging) {
            $r = self::_facebook()->api($method,$qs);
            return $r;
        }
    
        $qs['limit'] = $paging;
        $r = self::_facebook()->api($method,$qs);
        $data = $r['data'];

        while(($next=$r['paging']['next']) && count($data)<$limit){
            $next = xpAS::get(xpas::dissolve(xpAS::get(explode('?',$next),'1')),'until');
            $qs['limit'] = $limit-count($data)<$paging ? $limit - count($data) :$paging;
            $qs['until'] = $next;
            $r = self::_facebook()->api($method,$qs);
            $data = xpAS::extend($data,$r['data']);
//            if($i++>5) break;
        }

        return array('data'=>$data);    
        
    }
    
    
    function _facebook(){
        global $CFG;
        
//            $CFG->facebook_api_id = '173314422723871';
//            $CFG->facebook_api_key = '093bd9019160142919167811d5678678';
//            $CFG->facebook_api_secret = 'da6e337a445ae7c4f15317edac31bef6';
//            $CFG->facebook_api_url = 'http://apps.facebook.com/homesforhopes/';        
        
// _debug(array('appId'=>$CFG->facebook_api_id,'secret'=>$CFG->facebook_api_secret,'cookie'=>true,));
//    _debug(self::$facebook);    
        return self::$facebook ? self::$facebook :     self::$facebook = new Facebook(array('appId'=>$CFG->facebook_api_id,'secret'=>$CFG->facebook_api_secret,'cookie'=>true,) );
                                 
    }
    
    function graph($method,  $params=array()){
        return self::_facebook()->api($method,$params);
    }
    
    function fql($query){
        $rs = self::_facebook()->_fql('/method/fql.query','POST',array("query"=>$query));
        return $rs;        
//        $fb = new Facebook(array(
//                        'appId'=>$CFG->facebook_api_id,
//                        'secret'=>$CFG->facebook_api_secret,
//                        'cookie'=>true,
//                 ));
//        
//        //$rs = $fb->_fql('/method/fql.query','POST',array("query"=>  ("SELECT uid, name, email, pic_square FROM user WHERE uid = me() OR uid IN (SELECT uid2 FROM friend WHERE uid1 = me() ) ")));
//        $rs = $fb->_fql('/method/fql.query','POST',array("query"=>  ("SELECT uid, name, email, pic_square FROM user WHERE uid = me()")));
//        //_debug($r);         
        
    }
    
}