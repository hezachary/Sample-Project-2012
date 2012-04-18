<?php

class user{
    protected $id;
    public $username;
    public $password;
    public $firstname;
    public $lastname;
    public $email;
    public $icq;
    public $skype;
    public $yahoo;
    public $aim;
    public $msn;
    public $phone1;
    public $phone2;
    public $department;
    public $address;
    public $city;
    public $country;
    public $lang;
    public $timezone;
    public $firstaccess;
    public $currentlogin;
    public $lastlogin;
    public $currentip;
    public $lastip;
    public $secret;
    public $picture;
    public $url;
    public $description;
    public $timemodified;
    public $role;
    
    public $pw_org;
    
    public $smtQuery;
    public $db;
    
    public static $CURRENT_USER;
    private $_current_user = false;
    public static function get_handle(){
        if(!self::$CURRENT_USER){
            self::$CURRENT_USER = new user();
            self::$CURRENT_USER->_current_user = true;
        }
        return self:: $CURRENT_USER;
    }
    
    public static $aryRole = array(
                                    'staff'=>'staff',
                                    'admin'=>'admin'
                                    );

    public function __construct(){
        global $CFG;
        $this->smtQuery = new smarty_query();
        $this->db = Zend_Db::factory($CFG->dbType, $CFG->db);
    }
    
    public function login(){
        $this->convertPw();
        $this->smtQuery->assign('username', $this->username);
        $this->smtQuery->assign('password', $this->password);
        $strQuery = $this->smtQuery->fetch('user.login.sql');
        $this->smtQuery->clear_all_assign();
        
        $aryResult = $this->db->fetchRow($strQuery);
        
        if(is_array($aryResult) && sizeof($aryResult) > 0){
            $aryResult['lastlogin'] = $aryResult['currentlogin'];
            $aryResult['lastip'] =$aryResult['currentip'];
            $aryResult['currentlogin'] = time();
            $aryResult['currentip'] = retrieveUserIp();
            
            self::setUser($aryResult);
            self::getUser($aryResult);
            foreach ($aryResult as $var => $value){
                $this->$var = $value;
            }
            $this->updateLoginTrace();
            return true;
        }else{
            return false;
        }
    }
    
    private function updateLoginTrace(){
        $this->smtQuery->assign('id', $this->id);
        $this->smtQuery->assign('currentlogin', $this->currentlogin);
        $this->smtQuery->assign('lastlogin', $this->lastlogin);
        $this->smtQuery->assign('currentip', $this->currentip);
        $this->smtQuery->assign('lastip', $this->lastip);
        $strQuery = $this->smtQuery->fetch('user.updateLoginTrace.sql');
        $this->smtQuery->clear_all_assign();
        
        $objStatement = $this->db->query($strQuery);
        
        return $objStatement->rowCount() > 0?true:false;
    }
    
    
    public static function getUser($aryUser = array()){
        $aryUser = (isset($aryUser) && is_array($aryUser) && sizeof($aryUser)>0)?$aryUser:$_SESSION['user'];
        
        if(isset($aryUser) && is_array($aryUser)){
            $objCurrentUser = self::get_handle();
            foreach ($aryUser as $var => $value){
                $objCurrentUser->$var = $value;
            }
            $objCurrentUser->lastlogintime = date('l jS \of F Y h:i:s A', $objCurrentUser->lastlogin);
        }
    }
    
    public static function setUser($aryUser){
        $_SESSION['user'] = $aryUser;
    }
    
    public function updateUser(){
        $this->convertPw();
        $this->smtQuery->assign('USER', $this);
        $strQuery = $this->smtQuery->fetch('user.updatePw.sql');
        $this->smtQuery->clear_all_assign();
        
        $objStatement = $this->db->query($strQuery);
        
        return $objStatement->rowCount() > 0?true:false;
    }
    
    private function convertPw(){
        $this->password = sha1(base64_encode($this->pw_org));
//        $this->password = sha1(base64_encode('123456'));
//        echo $this->password;die();
    }
    
    static public function unsetUser(){
        unset($_SESSION['user']);
    }
}

?>