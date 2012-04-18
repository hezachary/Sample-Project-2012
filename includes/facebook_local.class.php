<?php
require_once($CFG->includes.'Facebook/facebook.php');

class facebook_local extends Facebook {
    
    function __construct(){
        global $CFG;
        $aryConfig = array(
            'appId'  => $CFG->facebook_api_id,
            'secret' => $CFG->facebook_api_secret,
            'cookie' => true,
        );
        parent::__construct($aryConfig);
    }
    
    /*
     * Kind fast
     */
    public static function retrieveLikeByService($strUrl){
        $strCount = file_get_contents('http://api.facebook.com/restserver.php?method=links.getStats&urls='.urlencode($strUrl));
        $objXML = simplexml_load_string($strCount);
        $intCount = (int)$objXML->link_stat->total_count;
        return $intCount;
    }

    /*
     * Slow
     */
    public static function retrieveLikeByWidget($strUrl){
        $strCount = self::download_pretending('http://www.facebook.com/plugins/like.php?href='.urlencode($strUrl));
        preg_match('/connect_widget_not_connected_text[^>]+>\s*(?P<count>[0-9,]+)/', $strCount, $aryMatch);
        $intCount = (int)preg_replace('/[^0-9]+/', '', $aryMatch['count']);
        return $intCount;
    }

    public static function download_pretending($url,$user_agent='Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.13) Gecko/20100914 Firefox/3.5.13') {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt ($ch, CURLOPT_REFERER, 'http://www.facebook.com/plugins/like.php');
        $result = curl_exec ($ch);
        curl_close ($ch);
        return $result;
    }
    
}