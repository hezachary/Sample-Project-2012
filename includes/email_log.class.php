<?php
class email_log{
    const strTable = 'tblemail_log';
    const strEmailTitle = '{refer_email_name} wants you to be their neighbour';
    //const strEmailTitle = '';
    
    protected $id;
    
    public $refer_email;
    public $refer_email_name; 
    public $register_id; 
    
    public $datetime;
    public $ip_long;
    public $uniqueId;
    
    public $aryRegister;
    
    public static $aryFormField = array('refer_email',
                                        );
                                        
    public static $aryItem = array('accept', 'deny');
    
    public function __construct(){
        global $CFG;
        $this->smtQuery = new smarty_query();
        $this->db = Zend_Db::factory($CFG->dbType, $CFG->db);
    }

    public function setId($id){
        $this->id = (int)$id;
    }
    
    public function retrieveId(){
        return $this->id;
    }
    
    public function retrieveUniqueId(){
        if(!$this->uniqueId){
            $this->uniqueId = sha1(base64_encode(uniqid()));
        }
        return $this->uniqueId;
    }

    
    public function postIt($msg){
        global $CFG;
        //1. retrieve data
        
        
             //sent message to email
//        msg[]=array(
//                            'from'=>'admin@habi110002.com.au',
//                            'from_name'=>'admin',
//                            'to'=>$v['email'],
//                            'to_name'=>$v['name'],
//                            'subject'=>'habi110002 message',
//                            'message'=>$v['message'],
//                        );
                                
        
        
        
        //2. populate email
        $smtEmail = new smarty_page();
        $smtEmail->assign('CFG', $CFG);
        //2.1 send mail
        $smtEmail->assign('aryRegister', $this->aryRegister);
        $smtEmail->assign('refer_email', $this->refer_email);
        $smtEmail->assign('refer_email_name', $this->refer_email_name);
        $smtEmail->assign('uniqueId', $this->uniqueId);
        $strEmail = $smtEmail->fetch('referral_email.tpl');
        $strEmailTitle = self::strEmailTitle;
        
//        _d($strEmail,1);
//        _d($this->aryRegister['register_email'].' + '.$this->aryRegister['register_name'],1);
//        _d($this->refer_email.' + '.$this->refer_email_name,1);
//        _d($strEmailTitle,1);
//        _d($this->uniqueId);
        
        $objMail = new Zend_Mail('UTF-8');
        $objMail->setBodyHtml($strEmail);
        $objMail->setFrom($this->aryRegister['register_email'], str_replace(array('@', '.', ','), '',$this->aryRegister['register_name']));
        $objMail->addTo($this->refer_email, str_replace(array('@', '.', ','), '',$this->refer_email_name));
        $objMail->setSubject($this->refer_email_name.', '.$strEmailTitle);
        //$objMail->send();
        //_d('just sent');
        
        //3. Insert record

        return ;
    }    
    public function submitEmail(){
        $this->retrieveUniqueId();
        global $CFG;
        //1. retrieve data
        
        //validate if send duplicated email
        if(self::checkRegisterLogExist(array(    'register_id'    => $this->register_id,
                                                'refer_email'    => $this->refer_email,
                                            )
                                        )
        ){
            return array(false, array('email'=>'duplicated email',));
        }
        
        $aryItemList = array();
        
        //2. populate email
        $smtEmail = new smarty_page();
        $smtEmail->assign('CFG', $CFG);
        //2.1 send mail
        $smtEmail->assign('aryRegister', $this->aryRegister);
        $smtEmail->assign('refer_email', $this->refer_email);
        $smtEmail->assign('refer_email_name', $this->refer_email_name);
        $smtEmail->assign('uniqueId', $this->uniqueId);
        $strEmail = $smtEmail->fetch('referral_email.tpl');
        $strEmailTitle = str_replace('{refer_email_name}', $this->aryRegister['register_name'], self::strEmailTitle);
        
//        _d($strEmail,1);
//        _d($this->aryRegister['register_email'].' + '.$this->aryRegister['register_name'],1);
//        _d($this->refer_email.' + '.$this->refer_email_name,1);
//        _d($strEmailTitle,1);
//        _d($this->uniqueId);
        
        $objMail = new Zend_Mail('UTF-8');
        $objMail->setBodyHtml($strEmail);
        $objMail->setFrom($this->aryRegister['register_email'], str_replace(array('@', '.', ','), '',$this->aryRegister['register_name']));
        $objMail->addTo($this->refer_email, str_replace(array('@', '.', ','), '',$this->refer_email_name));
        $objMail->setSubject($strEmailTitle);
        $objMail->send();
        //_d('just sent');
        
        //3. Insert record
        $this->insertEmailLog();
        return array(true, array());
    }
    
    public function insertEmailLog(){
        $this->retrieveUniqueId();
        $this->datetime = date('Y-m-d H:i:s');
        $this->ip_long = ip2bigint(retrieveUserIp());
        $this->ip_long = $this->ip_long===false?0:$this->ip_long;
        $aryRow = array(                'refer_email'        => $this->refer_email,
                                        'refer_email_name'    => $this->refer_email_name,
                                        'register_id'        => $this->register_id,
                                        'register_name'        => $this->aryRegister['register_name'],
                                        'register_email'    => $this->aryRegister['register_email'],
                                        'mail_opened'        => 0,
                                        'mail_clicked'        => 0,
                                        'datetime'            => $this->datetime,
                                        'ip_long'            => $this->ip_long,
                                        'uniqueId'            => $this->uniqueId,
                    );
        $intRowsAffected = $this->db->insert(self::strTable, $aryRow);
        $this->setId($this->db->lastInsertId());
    }

    public static function updateEmailLogMailOpen($uniqueId){
        global $CFG;
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        
        $aryRow = array(                    'mail_opened'    => date('Y-m-d H:i:s'),
                    );
        
        $aryWhere['uniqueId = ?'] = $uniqueId;
        $aryWhere['mail_opened = ?'] = 0;
        $intRowsAffected = $db->update(self::strTable, $aryRow, $aryWhere);
        return;
    }
    
    public static function updateEmailLogMailClicked($uniqueId){
        global $CFG;
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        
        $aryRow = array(                    'mail_clicked'    => date('Y-m-d H:i:s'),
                    );
        
        $aryWhere['uniqueId = ?'] = $uniqueId;
        $aryWhere['mail_clicked = ?'] = 0;
        $intRowsAffected = $db->update(self::strTable, $aryRow, $aryWhere);
        return;
    }
    
    
    public function retrieveEmailLogByCondition($aryCondition){
        $objSelect = $this->db->select();
        $objSelect->from(self::strTable, array('id', 'refer_email','refer_email_name', 'register_id', 'register_name', 'register_email', 'mail_opened', 'mail_clicked', 'datetime', 'ip_long', 'uniqueId'));
        foreach($aryCondition as $strField => $strValue){
            $objSelect->where(preg_replace('/\s+/', '', $strField).' = ?', $strValue);
        }
        $objSelect->limit(1);
        
        $aryResult    = $this->db->fetchRow($objSelect->__toString());
        
        $this->setId($aryResult['id']);
        $this->refer_email        = $aryResult['refer_email'];
        $this->refer_email_name    = $aryResult['refer_email_name'];
        $this->register_id        = $aryResult['register_id'];
        $this->register_name    = $aryResult['register_name'];
        $this->register_email    = $aryResult['register_email'];
        $this->mail_opened        = $aryResult['mail_opened'];
        $this->mail_clicked        = $aryResult['mail_clicked'];
        $this->datetime            = $aryResult['datetime'];
        $this->ip_long            = $aryResult['ip_long'];
        $this->uniqueId            = $aryResult['uniqueId'];
        
        return $aryResult;
    }

    public static function retrievePromoDailyRateFromEmailLogByCampaignId(){
        global $CFG;
        
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        
        $objSelect = $db->select();
        $objSelect->from(self::strTable, array('log_date'=>'DATE(datetime)', 'total'=>'COUNT(id)',));
        $objSelect->group(array('DATE(datetime)'));
        $objSelect->order(array('DATE(datetime)'));
        
        $aryResult = $db->fetchAll($objSelect->__toString());
        
        return $aryResult;
    }
    
    public static function retrieveEmailLogList($aryCondition, $aryOrder){
        global $CFG;
        
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        
        $objSelect = $db->select();
        $objSelect->order($aryOrder);
        //_d($aryCondition,1);
        $smtQuery = new smarty_query();
        $smtQuery->assign('aryCondition', $aryCondition);
        $smtQuery->assign('strExtra', $objSelect->__toString());
        
        $strQuery = $smtQuery->fetch('email_log.retrieveEmailLogList.sql');
        //_d($strQuery,1);
        $aryResult = $db->fetchAll($strQuery);
        return $aryResult;
    }
    
    public static function checkRegisterLogExist($aryCondition){
        global $CFG;
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        
        $objSelect = $db->select();
        $objSelect->from(self::strTable, 'id');
        foreach($aryCondition as $strField => $strValue){
            $objSelect->where(preg_replace('/\s+/', '', $strField).' = ?', $strValue);
        }
        $objSelect->limit(1);
        
        $intExist = $db->fetchOne($objSelect->__toString());
        
        return $intExist?true:false;
    }

    public static function validatForm($aryPost){
        $aryExport = array();
        $aryMsg = array();
        
        foreach (self::$aryFormField as $var){
            if(isset($aryPost[$var])){
                $aryExport[$var] = !is_array($aryPost[$var])?trim($aryPost[$var]):$aryPost[$var];
                $aryExport[$var] = str_replace(array('%A', '%9'), array("\n", "\t"), $aryExport[$var]);
                if(fnProfanity($aryExport[$var])){
                    $aryMsg[$var] = 'Profanity contents';
                }
            }
        }
        
        $aryExport = clear_html($aryExport);
        
        $aryExport['refer_email'] = trim($aryExport['refer_email']);
        if(!fnEmailCheck($aryExport['refer_email'])){
            $aryMsg['refer_email'] = 'Invalid Receiver Email Address';
        }
    
        return array($aryExport, $aryMsg);
    }
}