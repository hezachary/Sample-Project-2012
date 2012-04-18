<?php

class login_log{
    public $date;
    public $attempt = 0;
    public $successed = 0;
    public $failed = 0;
    public $failed_rate;
    
    public $flag;
    
    public $smtQuery;
    public $db;
    
    public function __construct(){
        global $CFG;
        $this->smtQuery = new smarty_query();
        $this->db = Zend_Db::factory($CFG->dbType, $CFG->db);
    }
    
    public function maintainLoginLog($flag){
        $this->date = date('Y-m-d');
        $this->retrieveLoginLog();
        
        switch($flag){
            case 'successed':
            case 'failed':
                $this->flag = $flag;
                break;
        }
        
        if(!empty($this->date)){
            //update
            $this->updateLoginLog();
        }else{
            //insert
            $this->date = date('Y-m-d');
            $this->insertLoginLog();
        }
    }
    
    
    public function retrieveLoginLog(){
        $this->smtQuery->assign('date', $this->date);
        $strQuery = $this->smtQuery->fetch('login_log.retrieveLoginLog.sql');
        $this->smtQuery->clear_all_assign();
        
        $aryResult = $this->db->fetchRow($strQuery);
        
        $intTotal = $aryResult['failed']+$aryResult['successed'];
        
        $this->date        = $aryResult['date'];
        $this->attempt    = $aryResult['attempt'];
        $this->successed= $aryResult['successed'];
        $this->failed    = $aryResult['failed'];
        $this->failed_rate = $intTotal==0?0:round(($aryResult['failed']/$intTotal)*100, 2);
    }
    
    
    public function insertLoginLog(){
        $this->smtQuery->assign('query', $this);
        $strQuery = $this->smtQuery->fetch('login_log.insertLoginLog.sql');
        $this->smtQuery->clear_all_assign();
        
        $this->db->query($strQuery);
    }
    
    public function updateLoginLog(){
        $this->smtQuery->assign('query', $this);
        $strQuery = $this->smtQuery->fetch('login_log.updateLoginLog.sql');
        $this->smtQuery->clear_all_assign();
        
        $this->db->query($strQuery);
    }
}
?>