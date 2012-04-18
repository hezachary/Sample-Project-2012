<?php

class db_base{
    
    public static function _aryFontendOptions($lifetime = 60, $caching = true, $automatic_serialization = true, $automatic_cleaning_factor = 0){
        $aryFontendOptions = array(
                    'caching'                    => $caching,
                    'automatic_serialization'    => $automatic_serialization,
                    'automatic_cleaning_factor'    => $automatic_cleaning_factor,
        );
        if($lifetime != 999999){
            $aryFontendOptions['lifeTime'] = $lifetime;
        }else{
            $aryFontendOptions['lifeTime'] = null;
        }
        return $aryFontendOptions;
    }
    
    private static $intHashedDirectoryLevel = 0;
    public static function _aryBackendOptions(){
        global $CFG;
        return array('cache_dir' => $CFG->cache, 'hashed_directory_level' => self::$intHashedDirectoryLevel);
    }
    
    public static function populateCacheId($aryCacheId){
        $aryCacheIdTmp = array();
        self::$intHashedDirectoryLevel = 0;
        if(is_array($aryCacheId)){
            foreach($aryCacheId as $strCacheName => $strCacheId){
                if(is_int($strCacheName)){
                    $aryCacheIdTmp[] = $strCacheId;
                }else{
                    $aryCacheIdTmp[] = $strCacheName;
                    $aryCacheIdTmp[] = $strCacheId === null ? 'x' : $strCacheId;
                }
                self::$intHashedDirectoryLevel ++;
            }
            $aryCacheId = '_'.implode('_', $aryCacheIdTmp).'_';
        }
        return $aryCacheId;
    }
    
    const CACHE_TICKET = 'Ticket';
    public static function cache($strCacheId, $aryCallBack, $intCacheTime, $aryArg = array(), $blnForceRefresh = false){
        $strCacheId = self::populateCacheId($strCacheId);
        $objCache = Zend_Cache::factory('Core', 'File', self::_aryFontendOptions($intCacheTime), self::_aryBackendOptions());
        $aryResult = $objCache->load($strCacheId);
        $strCacheTicket == $objCache->load(self::CACHE_TICKET.'_'.$strCacheId);
        if($blnForceRefresh || $aryResult===false){
            if(is_array($aryCallBack)){
                $aryResult = call_user_func_array(array($aryCallBack[0], $aryCallBack[1]), $aryArg);
            }else{
                $aryResult = call_user_func_array(array($aryCallBack, 'populateCache'), $aryArg);
            }
            $strCacheTicket = time().uniqid();
            $objCache->save($aryResult, $strCacheId);
            $objCache->save($strCacheTicket, self::CACHE_TICKET.'_'.$strCacheId);
        }
        return $aryResult;
    }
    public static function verifyCacheTicket($strCacheId, $strCacheTicket){
        $objCache = Zend_Cache::factory('Core', 'File', self::_aryFontendOptions($intCacheTime), self::_aryBackendOptions());
        if(is_array($strCacheId)){
            $strCacheId = self::populateCacheId($strCacheId);
        }
        return $strCacheTicket == $objCache->load(self::CACHE_TICKET.'_'.$strCacheId);
    }
    
    public function setId($id){
        $this->id = (int)$id;
    }
    
    public function retrieveId(){
        return $this->id;
    }
    
    
    public function __construct(){
        global $CFG;
        $this->smtQuery = new smarty_query();
        $this->db = Zend_Db::factory($CFG->dbType, $CFG->db);
    }
    
    public static function retrieveFieldByCondition($strTable, $strId, $strField = 'id', $strSelectField = 'id'){
        global $CFG;
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        $objSelect = $db->select();
        
        $objSelect->from($strTable, $strSelectField);
        $objSelect->where($db->quoteIdentifier($strField).' = ?', $strId?$strId:'');
        $objSelect->limit(1);
        $strQuery = $objSelect->__toString();
        $result = $db->fetchOne($strQuery);
        
        return empty($result)?false:$result;
    }
    
    public static function removeData($strTable, $strField = 'id', $strId){
        global $CFG;
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        
        $result = $db->delete($strTable, $db->quoteIdentifier($strField).' = '.$db->quote($strId?$strId:''));
        
        return empty($result)?false:$result;
    }
    
    public static function retrieveList($strTable, $aryCondition, $intPageNum, $intPageStep, $arySelectFieldList, $blnCountMode = false, $aryOrder = array()){
        //1. retrieve lists
        global $CFG;
        $db = Zend_Db::factory($CFG->dbType, $CFG->db);
        
        $objSelect = $db->select();
        $aryCondition = is_array($aryCondition) ? $aryCondition : array();
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
                        $arySubCondition[] = $db->quoteIdentifier($aryData[0]).' '.strtoupper($aryData[1]).' '.($aryData[2]=='NULL'?'NULL':($aryData[3] ? $db->quoteIdentifier($aryData[2]) : $db->quote($aryData[2]))).'';
                        
                    }
                    $objSelect->where(implode(' OR ', $arySubCondition));
                }else{
                    $objSelect->where($db->quoteIdentifier($strField).' IN (?)', $strValue);
                    break;
                }
            }else{
                $objSelect->where($db->quoteIdentifier($strField).' = ?', $strValue);
            }
        }
        
        $intTotalPage = 1;
        
        $intPageStep = (int)$intPageStep;
        
        $objSelect->order($aryOrder);
        //global $CFG; echo $CFG->_d == 1 ? $objSelect->__toString() : null ;
        if( $intPageStep > 0 || $blnCountMode == true){
            $objSelectCount = clone $objSelect;
            $objSelectCount->from($strTable, 'COUNT(*) AS count_id');
            
            $intPageStep = abs($intPageStep)?abs($intPageStep):1;
            
            $intTotalRow = $db->fetchOne($objSelectCount->__toString());
            
            if($blnCountMode == true){
                return $intTotalRow;
            }
            
            $objSelect->from($strTable, $arySelectFieldList);
            $intTotalPage =  (int)ceil($intTotalRow/$intPageStep);
            
            $intPageNum = $intPageNum > $intTotalPage ? $intTotalPage : $intPageNum;
            $intPageNum = $intPageNum < 1 ? 1 : $intPageNum;
            $objSelect->limitPage($intPageNum, $intPageStep);
            $aryResult = $db->fetchAll($objSelect->__toString());
            return array($aryResult, $intPageNum, $intTotalPage);
        }else if( $intPageStep == 0 ) {
            $objSelect->from($strTable, $arySelectFieldList);
            $objSelect->limit(1);
            return $db->fetchRow($objSelect->__toString());
        }else if( $intPageStep < 0 ) {
            $objSelect->from($strTable, $arySelectFieldList);
            $objSelect->limit(abs($intPageStep));
            return $db->fetchAll($objSelect->__toString());
        }
        
    }
}