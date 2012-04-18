<?php

/* singleton : it would be nice if it's php5
*/
function DBHandle($host='',$db='',$user='',$pwd=''){
    static $__db_sql_handle=null;
    if(!$__db_sql_handle)
        $__db_sql_handle = new db_sql($host,$db,$user,$pwd);
    return     $__db_sql_handle ;
}

function clean_text($text) {
    if (empty($text) or is_numeric($text)) {
       return (string)$text;
    }

    $text = preg_replace('/[^0-9A-Za-z_\. &;,]*/', '', $text);
    
    return $text;
}

function clean_value_js($text) {
    $strJsProtocol = 'javascript\s*:';
    return clean_js($text, $strJsProtocol);
}

function clean_content_js($text) {
    $strJsProtocol = '((href\s*=\s*(\'|\")?)\s*javascript\s*:)';
    return clean_js($text, $strJsProtocol, '$2');
}

function clean_js($text, $strJsProtocol, $strReplace = ''){
    if (empty($text) or is_numeric($text)) {
       return (string)$text;
    }
    $aryJsEventList = array(    'onAbort',
                                'onBlur',
                                'onChange',
                                'onClick',
                                'onDblClick',
                                'onDragDrop',
                                'onError',
                                'onFocus',
                                'onKeyDown',
                                'onKeyPress',
                                'onKeyUp',
                                'onLoad',
                                'onMouseDown',
                                'onMouseMove',
                                'onMouseOut',
                                'onMouseOver',
                                'onMouseUp',
                                'onMove',
                                'onReset',
                                'onResize',
                                'onSelect',
                                'onSubmit',
                                'Unload',
                                'onUnload');
    
    $strJsEventList = implode('\s*=|', $aryJsEventList);
    
    return preg_replace('/'.$strJsEventList.'|'.$strJsProtocol.'/i', $strReplace, $text);
}

function safe_html($aryTextList, $aryWhiteList = array()) {
    foreach ($aryTextList as $strKey => $strText){
        if(in_array($strKey, $aryWhiteList) || empty($strText) || is_numeric($strText)){
            continue;
        }else{
            $aryTextList[$strKey] = htmlentities($strText, ENT_QUOTES);
        }
    }
    return $aryTextList;
}

function clear_html($aryTextList, $aryWhiteList = array(), $aryBlackList = array()) {
    foreach ($aryTextList as $strKey => $strText){
        if(isset($aryBlackList) && is_array($aryBlackList) && sizeof($aryBlackList) > 0){
            if(in_array($strKey, $aryBlackList) && !empty($strText) && !is_numeric($strText)){
                $aryTextList[$strKey] = htmlentities(strip_tags($strText), ENT_QUOTES);
            }
            continue;
        }
        if(isset($aryWhiteList) && is_array($aryWhiteList) && sizeof($aryWhiteList) > 0){
            if(!in_array($strKey, $aryWhiteList) && !empty($strText) && !is_numeric($strText)){
                $aryTextList[$strKey] = htmlentities(strip_tags($strText), ENT_QUOTES);
            }
            continue;
        }
        
        $aryTextList[$strKey] = htmlentities(strip_tags($strText), ENT_QUOTES);
    }
    return $aryTextList;
}

function clear_wrong_text($aryTextList, $aryFieldList = array()) {
    $aryWordMappingList = array("\x92" => '\'', "\x96" => '-',);
    $aryKeyList = array_keys($aryWordMappingList);
    $aryReplaceList = array_values($aryWordMappingList);
    foreach ($aryTextList as $strKey => $strText){
        if(is_string($strText)){
            $aryTextList[$strKey] = str_replace($aryKeyList, $aryReplaceList, $strText);
        }else{
            if(is_array($aryFieldList)){
                if(sizeof($aryFieldList) > 0){
                    foreach ($aryFieldList as $strFieldName){
                        $aryTextList[$strKey][$strFieldName] = str_replace($aryKeyList, $aryReplaceList, $strText[$strFieldName]);
                    }
                }else{
                    foreach ($strText as $strFieldName => $strValue){
                        $aryTextList[$strKey][$strFieldName] = str_replace($aryKeyList, $aryReplaceList, $strValue);
                    }
                }
            }
        }
    }
    return $aryTextList;
}

function clear_html_string($strKey) {
    return current(clear_html(array($strKey)));
}

function fnSetHighlight($aryText, $strKeywords, $aryNameList = array(), $strFormat = '<span class="matched">$1</span>'){
    $strKeywords = preg_replace('/(\W)/', '\$1', $strKeywords);
    $aryKeyWords = explode(' ', $strKeywords);
    
    $strReg = implode('|', $aryKeyWords);
    
    foreach ($aryText as $strKey => $strText){
        if(isset($aryNameList) && is_array($aryNameList) && sizeof($aryNameList) > 0){
            if(in_array($strKey, $aryNameList) && !empty($strText)){
                $aryText[$strKey] = preg_replace('/('.$strReg.')/i', $strFormat, $strText);
            }
        }else{
            $aryText[$strKey] = preg_replace('/('.$strReg.')/i', '<span class="matched">$1</span>', $strText);
        }
    }
    return $aryText;
}

function fnSetFrontEndLink($id){
    return 'index.php?pid='.(int)$id;
    
}
function fnProcessData($strData){
    $aryData = explode(',', $strData);
    
    $aryExport = array();
    foreach ($aryData as $value){
        $aryTmp = explode('.', $value);
        $aryExport[(int)$aryTmp[0]] = $aryTmp[1]?(int)$aryTmp[1]:(int)$aryTmp[0];
    }
    return array_flip($aryExport);
}

function fnArySetTree($ary, $node = 0, $aryRst = array()){
    foreach ($ary as $key => $value){
        if ($value['node']== $node){
            $aryTmp[(string)$value['pid']][(string)$value['id']] = array();
            unset($ary[$key]);
        }
    }
    if($node == 0){
        $aryRst = $aryTmp;

    }
    foreach($aryRst as $key_Rst => $value_Rst){
        foreach($aryTmp as $key_Tmp => $value_Tmp){
            if($key_Rst==$key_Tmp){
                $aryRst[$key_Rst]=$value_Tmp;
            }
        }
    }

    if(sizeof($ary) > 0){
        foreach($aryRst as $key_Rst => $value_Rst){
            $aryRst[$key_Rst] = fnArySetTree($ary, $node+1, $value_Rst);
        }
    }
    return $aryRst;
}

function fnArySetNode($ary, $aryOrg, $node = 0){
    $ary_1 = $ary;
    $ary_2 = $ary;
    $ary_3 = array();
    foreach($ary_1 as $key_1 => $value_1){
        foreach($ary_2 as $key_2 => $value_2){
            if($value_1['pid']==$value_2['id']){
                $ary_3[]=$ary_1[$key_1];
                unset($ary_1[$key_1]);
            }
        }
    }
    foreach($ary_1 as $key_1 => $value_1){
        foreach($aryOrg as $key_Org => $value_Org){
            if ($value_1['id']==$value_Org['id']){
                $aryOrg[$key_Org]['node']=$node;
            }
        }
    }
    if(sizeof($ary_3) > 0){
        $aryOrg = fnArySetNode($ary_3, $aryOrg, $node+1);
    }
    return $aryOrg;
}

function retrieveUserIp(){
    if (empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
        $userip = $_SERVER['REMOTE_ADDR'];
    }else{
        $userip = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        $userip = $userip[0];
    }
    return $userip;
}

function _d($v, $s = true, $d=false){
    global $CFG;
    if($CFG->debug == 'ALL' || ($CFG->debug == 'ON' && $_REQUEST['_d'])){
        $r = dechex(rand(200, 230));
        $g = dechex(rand(200, 230));
        $b = dechex(rand(200, 230));
        echo "<pre style='background-color:#$r$g$b;'>";
        var_dump(get_class($v));
        if(get_class($v))var_dump(get_class_methods($v));
        if($s)var_dump($v);
        echo "</pre><hr/>";
        if($d)die();
    }
}

function fnInputConfig($dir, &$config){
    global $CFG;
    $dir = $dir.'/mod/';
    //Loop through the files and include them
    if($dir_handle = opendir($dir)){
        while (false !== ($file = readdir($dir_handle))) {
            if (is_dir($dir.'/'.$file) && file_exists($dir.'/'.$file.'/config.php')) {
                include($dir.'/'.$file.'/config.php');
                if(!is_dir($CFG->data.$file)){
                    //_d($CFG->data.$file);
                    mkdir($CFG->data.$file, 0700);
                }
            }
        }
        if(!is_dir($CFG->data.'main')){
                    //_d($CFG->data.$file);
                    mkdir($CFG->data.'main', 0700);
        }
        closedir($dir_handle);
    }
    if(is_array($config)){
        foreach ($config as $section=>$onjSettings){
            $config[$section]->tpl_path = $CFG->mod_path.'admin/mod/'.$section.'/';
            $config[$section]->dir_path = $CFG->mod_path.'admin/mod/'.$section.'/';
        }
    }
}

//Check Email or Url function, url including http, https, ftp protocols and port number.
//But, username and password format is not acceptable, such as: ftp://username:password@somesite:21/somefolder/somefile.zip
//Return array
function net_chk($value, $type) {
    //Validation email address,
    //$value,
    //$type="email" or "url"
    switch ($type) {
        case "email":
            $validation="^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
            break;
        case "url":
            $validation="^(http|https|ftp):\/\/(([a-z0-9][a-z0-9_-]*)(\.[a-z0-9][a-z0-9_-]*)+)([:][0-9]{2,5})?\/";
        default:
            break;
    }

    if(eregi($validation, $value)) {
        return true;
    }else{
        return false;
    }
}

function fnEmailCheck($strEmail, $strict_mode=false){
    $pattern_normal = '/^([a-z0-9-_]+(?:\.?[a-z0-9-_])*)@((?:[a-z0-9-_]+\.)+(?:[a-z0-9-_]{2,6}))$/i';
    $pattern_strict = '/^((?:[a-z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+(?:\.[a-z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x20\x21\x23-\x5b\x5d-\x7f]|\\\[\x01-\x09\x0b\x0c\x0e-\x7f])*"))@((?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\]))$/i';
    $blnResult = preg_match ($strict_mode?$pattern_strict:$pattern_normal, $strEmail, $aryMatch);
    return $blnResult?(strlen($aryMatch[1])>64||strlen($aryMatch[2])>255?0:1):0;
}


function fnPhoneCheck($phone) {
    $phoneReg = "/^(\+\d{2}[ \-]{0,1}){0,1}(((\({0,1}[ \-]{0,1})0{0,1}\){0,1}[2|3|7|8]{1}\){0,1}[ \-]*(\d{4}[ \-]{0,1}\d{4}))|(1[ \-]{0,1}(300|800|900|902)[ \-]{0,1}((\d{6})|(\d{3}[ \-]{0,1}\d{3})))|(13[ \-]{0,1}([\d \-]{5})|((\({0,1}[ \-]{0,1})0{0,1}\){0,1}4{1}[\d \-]{8,10})))$/";
    return preg_match($phoneReg, $phone);   
}

function fnBarcodeCheck($barcode) {
    $barcode = preg_replace("/\s/", '', $barcode);
    if(strlen($barcode) != 13 && strlen($barcode) != 8){
        return 0;
    }
    $barcodeReg = "/^\d+$/";
    return preg_match($barcodeReg, $barcode);   
}

function fnRankCheck($value, $max){
    return round($value*10/($max<1?1:$max));
}

function fnCommentsCheck($strText, $intWords){
    $strText = preg_replace('/\s+/', ' ', trim($strText));
    return sizeof(explode(' ', $strText)) > $intWords ? false : true;
}

function fnProfanity($string) {
        global $CFG;
        $combinations = array(
                'like'=>array('boy','men','kid','child'),
                'touch'=>array('boy','girl','kid','child'),
                'fid'=>array('kid','child'),
                'fondle'=>array('boy','girl','kid','child'),
                'beat'=>array('wife','meat','mis'),
                'rug'=>array('munch')
        );
        $strict = array(
                'fuck','fk','fark','fcuk','blow','suck','lick','piss','wank','masti','masterbate', //adjectives
                'shit','ass','arse','bitch','bastard','retard','rapist', //nouns
                'dick','penis','cock','knob', //male genitals
                'vagina','cunt','cnt','pussy','snatch','minge', //female genitals
                'fag','homo','gay','pedo','lesb','dyke', //sexual preference
                'nigger' //race
        );
        foreach($combinations as $key => $val) {
                $regex[] = "($key\s+".implode("\s+|$key\s+",$val)."\s+|".implode("\s+$key\s+|",$val)."\s+$key\s+)";
        }
        //$regex[] = '('.implode("|",$strict).')';
        $strWordList = str_replace(array("\r\n", "\n", "\r"), ";;;;", file_get_contents($CFG->includes.'settings/badwords.txt'));
        $strWordList = preg_replace(array('/\s+/'), array('\s+'), $strWordList);
        $aryWordList = explode(";;;;", $strWordList);
        //$regex = array();
        $regex[] = '('.implode("\s+|\s+",$aryWordList).')';
        $profanityReg = "/".implode("|",$regex)."/ix";
        
        if(preg_match($profanityReg, $string)) return true;
        
        return false;
}

function ip2bigint($ip){
    $long = ip2long($ip);
    if ($long == -1 || $long === FALSE) {
        $long = ip2long6($ip);
        if($long == -1 || $long === FALSE){
            return false;
        }
    } else {
        $bin = sprintf("%u\n", ip2long($ip));
    }
    return $bin; 
}

function ip2long6($ipv6) {
    $ip_n = inet_pton($ipv6);
    $bits = 15; // 16 x 8 bit = 128bit
    while ($bits >= 0) {
        $bin = sprintf("%08b",(ord($ip_n[$bits])));
        $ipv6long = $bin.$ipv6long;
        $bits--;
    }
    return gmp_strval(gmp_init($ipv6long,2),10);
}

function fnRetrieveForOption($aryOption){
    $aryExport = array();    
    foreach($aryOption as $strOption){
        $aryExport[(string)$strOption] = (string)$strOption;
    }
    return $aryExport;
}

function mergeString(){
    $aryString = func_get_args();
    return implode('',$aryString);
}

function fnPopVar($strName, $strType){
    return str_replace(array("\r\n", "\n", "\r", "\t", " "), '', $strType).str_replace(array("\r\n", "\n", "\r", "\t", " "), '', ucwords($strName));
}

function stripslashes_request(){
    stripslashes_deep_ref($_GET);
    stripslashes_deep_ref($_POST);
    stripslashes_deep_ref($_REQUEST);
    stripslashes_deep_ref($_COOKIE);
}

function stripslashes_deep_ref(&$value){
    $v = $value;
    $value = stripslashes_deep($v);
}

function stripslashes_deep($value){
    return (is_array($value))?array_map('stripslashes_deep', $value):stripslashes($value);
}

function fnAryLoc(){
    $aryDataList = func_get_args();
    $intLength = sizeof($aryDataList);
    $dataReturnValue = $aryDataList[0];
    if(is_object($aryDataList[0])){
        for($i = 1; $i < $intLength; $i ++){
            $dataReturnValue = $dataReturnValue->$aryDataList[$i];
        }
    }elseif(is_array($aryDataList[0])){
        for($i = 1; $i < $intLength; $i ++){
            $dataReturnValue = $dataReturnValue[$aryDataList[$i]];
        }
    }
    //return ary data for smarty
    
    return $dataReturnValue;
}

function fnRN($lenMin = 5, $lenMax = null){
    $intStart = 97;
    $strExport = '';
    $i = 0;
    $len = $lenMin < $lenMax?rand($lenMin, $lenMax):$lenMin;
    while($i < $len){
        $strExport .= chr(rand($intStart, $intStart+25));
        $i ++;    
    }
    return $strExport;
}

function hex2bin($str) {
    $bin = "";
    $i = 0;
    do {
        $bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
        $i += 2;
    } while ($i < strlen($str));
    return $bin;
}

function fnRetrieveYoutubeId($strYouTubeUrl){
    preg_match('/www\.youtube\.com\/(?:v\/|watch\?[^\/\"\\\']*v\=)(?P<youtube_id>[A-Za-z0-9_\-]{11})/i', $strYouTubeUrl, $aryMatch);
    return $aryMatch['youtube_id'];
}

if (!function_exists('json_decode')) {
    function json_encode($aryData){
        Zend_Json::$useBuiltinEncoderDecoder = true;
        return Zend_Json::encode($aryData);
    }

    function json_decode($aryData){
        Zend_Json::$useBuiltinEncoderDecoder = true;
        return Zend_Json::decode($aryData);
    }
}

function fnCheckSSL(){
    global $CFG;
    $blnReqSSL  = (in_array(substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')), $CFG->ssl_page_list)) ? true : false;
    $blnIsSSL   = !empty($_SERVER['HTTPS']);
    if($blnReqSSL ^ $blnIsSSL){
        header('Location: '.($blnReqSSL ? 'https':'http').'://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']); 
    }else{
        $CFG->is_ssl_page   = $blnIsSSL;
    }
}

function out($str){
    print_r($str);
    echo "\r\n\r\n";
}

function fnShuffleAssoc( $array ){
   $keys = array_keys( $array );
   shuffle( $keys );
   return array_merge( array_flip( $keys ) , $array );
} 

function fnRetrieveThumb($strFileUrl){
    $strFileName = substr($strFileUrl, 0, strrpos($strFileUrl, '.'));
    $strFileExt = substr($strFileUrl, strrpos($strFileUrl, '.'));
    return $strFileName.$strAddOn.$strFileExt;
}
function fnRemoveElement($ary, $key){
    unset($ary[$key]);
    return $ary;
}
function fnRetrieveFileName($intEntryId, $strFileName, $strType){
    global $CFG;
    $strTable = 'tblEntryFileExtra';
    
    $strDBFileName = false;
    switch($strType){
        case 'video':
            $strDBFileName = 'resized_'.preg_replace('/\s+/', '_', $strFileName);
            $strDBFileName = substr($strDBFileName, 0, strrpos($strDBFileName, '.')).'.flv';
            
            //$strDBFileName = false;
            break;
        case 'web':
            $strDBFileName = 'web_'.$strFileName;
            break;
        case 'thumb':
            $strDBFileName = 'thumb_'.$strFileName;
            break;
    }
    return $strDBFileName;
}

function fnBuildRewriteUrl(){
    $aryReq = func_get_args();
    $aryInput = array_shift($aryReq);
    
    $aryExtra = array();
    foreach($aryInput as $key => $value){
        if($key == 'search_field'){
            $aryExtra[$key] = $value;
        }else{
            array_unshift($aryReq, $value);
            array_unshift($aryReq, $key);
        }
    }
    
    return implode('/',$aryReq).(sizeof($aryExtra) ? '?'.http_build_query($aryExtra ,'','&amp;') : '');
}

require_once($CFG->includes.'xpDebug.php');