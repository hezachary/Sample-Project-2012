<?php
class facebook_share{
    const strTable = 'tblFacebookShare';
    protected $id;
    public $name;
    public $first_name;
    public $last_name;
    public $gender;
    public $email_address;
    public $facebook_id;
    public $product_id;
    public $product_brand;
    public $product_desc;
    public $timestamp;
    public $last_download;
    
    public static $aryRegisterFormField = array(
                                            'facebook_id',
                                            'email_address',
                                            'product_id',
                                        );
    
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
    
    public function insertFacebookShare(){
        $this->timestamp = date('Y-m-d H:i:s');
        $this->last_download = '0';
        $aryRow = array(                    'name'            => $this->name,
                                            'first_name'    => $this->first_name,
                                            'last_name'        => $this->last_name,
                                            'gender'        => $this->gender,
                                            'email_address'    => $this->email_address,
                                            'facebook_id'   => $this->facebook_id,
                                            'product_id'    => $this->product_id,
                                            'product_brand' => $this->product_brand,
                                            'product_desc'    => $this->product_desc,
                                            'timestamp'        => $this->timestamp,
                                            'last_download'    => $this->last_download,
                    );
        $intRowsAffected = $this->db->insert(self::strTable, $aryRow);
        $this->setId($this->db->lastInsertId());
        return $intRowsAffected?$this->retrieveId():false;
    }
    
    public static function retrieveShareListByConditionAndPageNumber($aryCondition, $intPageNum, $intPageStep, $aryOrder, $arySelectFieldList = array(), $blnCountMode = false){
        global $CFG;
        
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        
        $objSelect = $db->select();
        
        foreach($aryCondition as $strField => $strValue){
            if(is_array($strValue)){
                if(is_array(current($strValue))){
                    reset($strValue);
                    $arySubCondition = array();
                    foreach($strValue as $aryData){
                        switch(strtoupper($aryData[1])){
                            case '=':
                            case '<':
                            case '>':
                            case '>=':
                            case '<=':
                            case 'IS':
                            case 'IS NOT':
                            case 'LIKE':
                            break;
                            default:
                                $aryData[1] = '=';
                            break;
                        }
                        $arySubCondition[] = $db->quoteIdentifier($aryData[0]).' '.strtoupper($aryData[1]).' '.($aryData[2]=='NULL'?'NULL':$db->quote($aryData[2])).'';
                        
                    }
                    $objSelect->where(implode(' OR ', $arySubCondition));
                }else{
                    $objSelect->where($db->quoteIdentifier($strField).' IN ?', $strValue);
                    break;
                }
            }else{
                $objSelect->where($db->quoteIdentifier($strField).' = ?', $strValue);
            }
        }
        
        $intTotalPage = 1;
        
        $intPageStep = (int)$intPageStep;
        
        if( $intPageStep > 0 || $blnCountMode == true){
            $objSelectCount = clone $objSelect;
            $objSelectCount->from(self::strTable, 'COUNT(id) AS count_id');
            
            $intPageStep = abs($intPageStep)?abs($intPageStep):1;
            $intTotalRow = $db->fetchOne($objSelectCount->__toString());
            
            if($blnCountMode == true){
                //_d($objSelectCount->__toString(),1);
                return array($intTotalRow, 1, 1);
            }
            
            $intTotalPage =  (int)ceil($intTotalRow/$intPageStep);
            
            $intPageNum = $intPageNum > $intTotalPage ? $intTotalPage : $intPageNum;
            $intPageNum = $intPageNum < 1 ? 1 : $intPageNum;
            $objSelect->limitPage($intPageNum, $intPageStep);
        }else if( $intPageStep == 0 ) {
            $objSelect->limit(1);
        }else if( $intPageStep < 0 ) {
            $objSelect->limit(abs($intPageStep));
        }
        
        $objSelect->from(self::strTable, sizeof($arySelectFieldList)>0?$arySelectFieldList:array(
                                            'id',
                                            'name',
                                            'first_name',
                                            'last_name',
                                            'gender',
                                            'email_address',
                                            'facebook_id',
                                            'product_id',
                                            'product_brand',
                                            'product_desc',
                                            'timestamp',
                                            'last_download')
                        );
        $objSelect->order($aryOrder);
        //_d($objSelect->__toString(),1);
        $aryResult = $db->fetchAll($objSelect->__toString());
        
        return array($aryResult, $intPageNum, $intTotalPage);
    }
    public static function validatFormForShare($aryPost){
        list($aryExport, $aryMsg) = self::filterForm($aryPost, self::$aryRegisterFormField);
        
        list($aryExport, $aryMsg) = self::validatForm($aryExport, $aryMsg);
        return array($aryExport, $aryMsg);
    }

    public static function filterForm($aryPost, $aryField){
        $aryExport = array();
        $aryMsg = array();
        
        foreach ($aryField as $var){
            $aryPost[$var] = isset($aryPost[$var])?$aryPost[$var]:null;
            $aryExport[$var] = !is_array($aryPost[$var])?trim($aryPost[$var]):$aryPost[$var];
            if(fnProfanity($aryExport[$var])){
                $aryMsg[$var] = 'Profanity contents';
            }
        }
        
        $aryExport = clear_html($aryExport);
        $aryExport = clear_wrong_text($aryExport);
        
        return array($aryExport, $aryMsg);
    }
    
    public static function validatForm($aryExport, $aryMsg){
        if($aryExport['facebook_id']){
           $aryMe = Zend_Json::decode(file_get_contents('http://graph.facebook.com/'.$aryExport['facebook_id']));
           $aryExport['name'] = $aryMe['name'];
           $aryExport['first_name'] = $aryMe['first_name'];
           $aryExport['last_name'] = $aryMe['last_name'];
           $aryExport['gender'] = $aryMe['gender'];
        }else{
           $aryExport['name'] = '';
           $aryExport['first_name'] = '';
           $aryExport['last_name'] = '';
           $aryExport['gender'] = '';
        }
        
        $aryExport['email_address'] = trim($aryExport['email_address']);
        if(!fnEmailCheck($aryExport['email_address'])){
            $aryMsg['email_address'] = 'Invalid Email Address';
        }
        
        list($aryProductList) = product::retrieveProductList();
        
        if(strlen($aryExport['product_id']) > 0 && $aryProductList[$aryExport['product_id']]){
            $aryExport['product_brand'] = $aryProductList[$aryExport['product_id']]['Brand'];
            $aryExport['product_desc'] = $aryProductList[$aryExport['product_id']]['Description'];
        }else{
            $aryMsg['product_id'] = 'Please Select a Product to Share';
        }
        return array($aryExport, $aryMsg);
    }
    
}