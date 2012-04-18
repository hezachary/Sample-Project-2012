<?php
class service_action
{
    private static function _aryFontendOptions($lifetime = 60, $caching = true, $automatic_serialization = true, $automatic_cleaning_factor = true){
        return array(
                    'lifeTime'                   => $lifetime,
                    'caching'                    => $caching,
                    'automatic_serialization'    => $automatic_serialization,
                    'automatic_cleaning_factor'  => $automatic_cleaning_factor,
        );
    }
    
    private static function _aryBackendOptions(){
        global $CFG;
        return array('cacheDir' => $CFG->cache);
    }
    
    private static function _packageObject($strExplicitType){
        $aryErrorMsg = message_queue::get('error');
        $aryResultMsg = message_queue::get('msg');
        
        $objExport = new stdClass();
        $objExport->_explicitType = $strExplicitType;
        $objExport->msg = sizeof($aryErrorMsg) > 0 ? $aryErrorMsg : $aryResultMsg;
        $objExport->success = sizeof($aryErrorMsg) > 0 ? false : true;
        
        return $objExport;
    }
    
    /**
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.increased_credit = int <-- how much user spent
     * objExport.msg = string <-- error msg or how much user get
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
    public static $_requireScheduleCredit = ActionResponseScheduleCredit;
    public static function requireScheduleCredit()
    {
        return $objExport;
    }
    public static function verify_requireScheduleCredit($objData)
    {
        return $objExport;
    }
     **/
    
    /**
     * Input:
     * objData.map_item_id = int <-- map_item_id
     * objData.name = '' <-- map_item_name, empty string
     * 
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.id = int <-- map_item_id
     * objExport.credit = int <-- how much user spent
     * objExport.msg = string <-- error msg or how much user spend
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     * Example: 5.php
     * 
     **/
    public static $_buyMapItem = 'ActionResponseMapItem';
    public static function buyMapItem($aryData)
    {
        $objMapItem = new db_mapmatrix();
        $objMapItem->name = '';//$aryData['name'];
        $objMapItem->id = $aryData['map_item_id'];
        $objMapItem->buyMapItem();
        
        $aryMapMatrix = message_queue::pop('aryMapMatrix');
        
        $objCurrentResident = db_resident::get_handle();
        
        db_mapmatrix::retrieveOneMapMatrix($aryMapMatrix['id'], true);
        db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->id, true);
        
        $objExport = self::_packageObject(self::$_buyMapItem);
        $objExport->map_item_id = $aryMapMatrix['id'];
        $objExport->neighbourhood_id = $aryMapMatrix['neighbourhood_id'];
        $objExport->credit = $aryMapMatrix['credit'];
        return $objExport;
    }
    public static function verify_buyMapItem($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        
        $objCurrentResident = db_resident::get_handle();
        
        $objMapItem = new db_mapmatrix();
        $objMapItem->setId($objData->map_item_id);
        $objMapItem->name = $objData->name;
        
        $objNeighbourhood = new db_neighbourhood();
        $aryCondition = array();
        $aryCondition['id'] = $objMapItem->retrieveId();
        $aryCondition['neighbourhood_id'] = $objNeighbourhood->retrieveId();
        $aryMapItem = db_mapmatrix::retrieveList(db_mapmatrix::strTable, $aryCondition, 0, 0, array(
                                            'id',
                                            'neighbourhood_id',
                                            'x',
                                            'y',
                                            'map_element_type_id',
                                            'resident_id',
                                            'reserve_to_facebook_id',
                                            'timestamp',
                                            'name',
                                            'credit',
                                            'credit_for_sold',));
        
        $blnAvailable = false;
        $blnReserved = $aryMapItem['reserve_to_facebook_id'] && strtotime($aryMapItem['timestamp']) >= strtotime('now') ? true : false;
        
        if(!is_array($aryMapItem) || sizeof($aryMapItem) < 1){
            $aryMsg['MapItem'] = db_language::get('land_unexist');
        }elseif($aryMapItem['map_element_type_id'] == 1){
            $aryMsg['MapItem'] = db_language::get('land_not_for_sale');
        }elseif($aryMapItem['map_element_type_id'] == 3){
            $aryMsg['MapItem'] = db_language::get('land_sold_already');
        }elseif($blnReserved && $aryMapItem['map_element_type_id'] == 2 && $aryMapItem['reserve_to_facebook_id'] && $aryMapItem['reserve_to_facebook_id'] != $objCurrentResident->facebook_id){
            $aryMsg['MapItem'] = db_language::get('land_reserved');
        }elseif($aryMapItem['map_element_type_id'] == 2 && !$blnReserved){
            $blnAvailable = true;
        }elseif($aryMapItem['map_element_type_id'] == 2 && $blnReserved && $aryMapItem['reserve_to_facebook_id'] == $objCurrentResident->facebook_id){
            $blnAvailable = true;
        }
        
        if(!$blnAvailable){
            $aryCacheMapMatrix = db_mapmatrix::retrieveOneMapMatrix($aryMapItem['id']);
            if($aryCacheMapMatrix['map_element_type_id']!=$aryMapItem['map_element_type_id']){
                db_mapmatrix::retrieveOneMapMatrix($aryMapItem['id'], true);
            }
        }
        
        $blnMoneyEnough = false; 
        $intCreditReal = db_resident::retrieveFieldByCondition(db_resident::strTable, $objCurrentResident->facebook_id, 'facebook_id', 'credit');
        if($blnAvailable == true){
            if($intCreditReal >= $aryMapItem['credit']){
                $blnMoneyEnough = true;
            }else{
                $aryMsg['credit'] = db_language::get('not_enough_credit');
            }
        }
        $aryExportData['map_item_id'] = $objMapItem->retrieveId();
        return array($blnMoneyEnough&$blnAvailable, $aryMsg, $aryExportData);
    }
    
    /**
     * Input:
     * objData.map_item_id = int <-- map_item_id
     * objData.resident_element_id = int <-- resident element id
     * objData.mirror = int <-- 0/1
     * objData.file_name = string <--   only for file name contain [switch]
     *                                  such as: i_fences.png?part[0]=fences.png,0,0,0,123&part[1]=fences.png,201,0,1,123&part[2]=fences.png,0,129,1,813&part[3]=fences_gate.png,201,129,0,813&switch=1
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.id = int <-- map_item_id
     * objExport.resident_element_id = int <-- resident element id
     * objExport.credit = int <-- how much user spent
     * objExport.msg = string <-- error msg or how much user spend
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     * Example: 8.php
     **/
    public static $_buyBuildingItem = 'ActionResponseBuildingItem';
    public static function buyBuildingItem($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        //2. Update map resident id
        $objResidentFieldBuilding = new db_resident_field_building();
        $objResidentFieldBuilding->map_matrix_id = $aryData['map_item_id'];
        $objResidentFieldBuilding->resident_element_id = $aryData['resident_element_id'];
        $objResidentFieldBuilding->mirror = $aryData['mirror'];
        $objResidentFieldBuilding->timestamp = date('Y-m-d H:i:s');
        $objResidentFieldBuilding->enabled   = $objCurrentResident->id;
        if(is_array($aryData['rmove_list']) && sizeof($aryData['rmove_list']) > 0){
            //remove both Preset and previouse elements, and related tasks
            $objResidentFieldBuilding->removeElement($aryData['rmove_list'], $objCurrentResident);
        }
        $objResidentFieldBuilding->buyResidentFieldBuilding();
        
        $aryResidentElement = message_queue::pop('aryResidentElement');
        $intRewardRating = message_queue::pop('intRewardRating');
        $intLevel = message_queue::pop('intLevel');
        $intRewardCredit = message_queue::pop('intRewardCredit');
        $strPosition = message_queue::pop('strPosition');
        
        db_resident_field_building::retrieveBuildingListForOneMapMatrix($objResidentFieldBuilding->map_matrix_id, true);
        db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->id, true);
        
        $objNeighbourhood = new db_neighbourhood();
        
        $objExport = self::_packageObject(self::$_buyBuildingItem);
        $objExport->neighbourhood_id = $objNeighbourhood->retrieveId();
        $objExport->map_item_id = $objResidentFieldBuilding->map_matrix_id;
        $objExport->resident_element_id = $aryResidentElement['id'];
        $objExport->mirror = $objResidentFieldBuilding->mirror;
        $objExport->credit = $aryResidentElement['credit'];
        $objExport->reward_rating = $intRewardRating;
        $objExport->level = $intLevel;
        $objExport->reward_credit = $intRewardCredit;
        $objExport->position = $strPosition;
        
        return $objExport;
    }
    public static function verify_buyBuildingItem($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $aryExportData['rmove_list'] = array();
        
        $blnAvailable = true;
        $blnMoneyEnough = false;
        
        $objCurrentResident = db_resident::get_handle();
        $objNeighbourhood = new db_neighbourhood();
        
        $objMapItem = new db_mapmatrix();
        $objMapItem->setId($objData->map_item_id);
        $objMapItem->retrieve();
        
        if($objMapItem->map_element_type_id != 3 || $objMapItem->resident_id != $objCurrentResident->id){
            $aryMsg['MapItem'] = db_language::get('land_not_yours');
            $blnAvailable = false;
        }
        
        if($blnAvailable && $objMapItem->neighbourhood_id != $objNeighbourhood->retrieveId()){
            $aryMsg['Neighbourhood'] = db_language::get('land_unexist');
            $blnAvailable = false;
        }
        
        if($blnAvailable){
            $aryResidentFieldBuildingCondition = array();
            $aryResidentFieldBuildingCondition['map_matrix_id'] = $objMapItem->retrieveId();
            $aryResidentFieldBuildingSelect = array('id', 'resident_element_id', 'z_index');
            $aryResidentFieldBuildingList = db_resident_field_building::retrieveList(db_resident_field_building::strTable, $aryResidentFieldBuildingCondition, 0, -9999, $aryResidentFieldBuildingSelect);
            
            $aryResidentElementCondition = array();
            $aryResidentElementCondition['id'] = $objData->resident_element_id;
            $aryResidentElementSelect = array('id', 'name', 'resident_element_type_id', 'credit', 'file_name');
            $aryResidentElement = db_resident_field_building::retrieveList(db_resident_field_building::strResidentElementTable, $aryResidentElementCondition, 0, 0, $aryResidentElementSelect);
            
            if(!is_array($aryResidentElement) || sizeof($aryResidentElement) < 1){
                $aryMsg['ResidentElement'] = db_language::get('building_element_unexist');
                $blnAvailable = false;
            }else if($aryResidentElement['resident_element_type_id']%10!=3){
                $aryMsg['ResidentElement'] = db_language::get('resident_element_not_for_private');
                $blnAvailable = false;
            }else if($aryResidentElement['credit'] < 0){
                $aryMsg['ResidentElement'] = db_language::get('resident_element_not_for_sale');
                $blnAvailable = false;
            }else{
                $aryBuildingList = array();
                $aryResidentElementFileSettings = db_resident_field_building::parseFilename($aryResidentElement['file_name']);
                $aryChildElementZIndexList = array();
                if(isset($aryResidentElementFileSettings['query']['part']) && is_array($aryResidentElementFileSettings['query']['part'])){
                    foreach($aryResidentElementFileSettings['query']['part'] as $aryPartSetting){
                        $aryChildElementZIndexList[$aryPartSetting[4]] = true;
                    }
                }
                foreach($aryResidentFieldBuildingList as $aryResidentFieldBuilding){
                    if(floor($aryResidentFieldBuilding['z_index']/10) == floor($aryResidentElement['resident_element_type_id']/10)){
                        //$aryMsg['ResidentFieldBuilding'] = 'Please remove '.$aryResidentFieldBuilding['name'].' before build '.$aryResidentElement['name'];
                        //$blnAvailable = false;
                        //break;
                        if($aryResidentFieldBuilding['resident_element_id'] == $aryResidentElement['id']){
                            $aryMsg['ResidentElement'] = db_language::get('your_building');
                            $blnAvailable = false;
                        }else{
                            $aryExportData['rmove_list'][] = $aryResidentFieldBuilding['id'];
                        }
                    }
                    if($aryChildElementZIndexList[$aryResidentFieldBuilding['z_index']]){
                        $aryExportData['rmove_list'][] = $aryResidentFieldBuilding['id'];
                    }
                    if($aryResidentFieldBuilding['z_index'] > 110){
                        // z-index > 110 means anything z-index higher than tree
                        $aryBuildingList[] = $aryResidentFieldBuilding['z_index'];
                    }
                    if($aryResidentFieldBuilding['z_index'] > 10 && $aryResidentFieldBuilding['z_index'] < 111){
                        $aryExportData['rmove_list'][] = $aryResidentFieldBuilding['id'];
                    }
                }
                
                if($blnAvailable && !sizeof($aryBuildingList) && $aryResidentElement['resident_element_type_id'] != 213){
                    //$aryResidentElement['resident_element_type_id'] = 213 means house
                    $aryMsg['ResidentFieldBuilding'] = db_language::get('build_house_1st');
                    $blnAvailable = false;
                }
                
                //1. Check credit
                $intCreditReal = db_resident::retrieveFieldByCondition(db_resident::strTable, $objCurrentResident->facebook_id, 'facebook_id', 'credit');
                if($intCreditReal >= $aryResidentElement['credit']){
                    $blnMoneyEnough = true;
                }else{
                    $aryMsg['credit'] = db_language::get('not_enough_credit');
                }
                
                $aryExportData['resident_element_id'] = $aryResidentElement['id'];
            }
        }
        $aryExportData['map_item_id'] = $objMapItem->retrieveId();
        $aryExportData['mirror'] = (int)$objData->mirror ? 1 : 0;
        return array($blnMoneyEnough&$blnAvailable, $aryMsg, $aryExportData);
    }
    
    /**
     * Input:
     * objData.map_item_id = int <-- map_item_id
     * objData.resident_field_building_id = int <-- resident field building id
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.id = int <-- map_item_id
     * objExport.resident_element_id = int <-- resident element id
     * objExport.credit = int <-- how much user spent
     * objExport.msg = string <-- error msg or how much user spend
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     * Example: 9.php
     **/
    public static $_enableCommunityBuilding = 'ActionResponseCommunityBuilding';
    public static function enableCommunityBuilding($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        //2. Update map resident id
        $objResidentFieldBuilding = new db_resident_field_building();
        $objResidentFieldBuilding->setId($aryData['resident_field_building_id']);
        $objResidentFieldBuilding->resident_element_id   = $aryData['resident_element_id'];
        $objResidentFieldBuilding->map_matrix_id   = $aryData['map_matrix_id'];
        $objResidentFieldBuilding->enableCommunityBuilding();
        
        db_resident_field_building::retrieveBuildingListForOneMapMatrix($objResidentFieldBuilding->map_matrix_id, true);
        db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->id, true);
        
        $intRewardRating = message_queue::pop('intRewardRating');
        $intLevel = message_queue::pop('intLevel');
        $intRewardCredit = message_queue::pop('intRewardCredit');
        $strPosition = message_queue::pop('strPosition');
        
        $objExport = self::_packageObject(self::$_enableCommunityBuilding);
        $objExport->map_item_id = $aryData['map_matrix_id'];
        $objExport->resident_element_id = $aryData['resident_element_id'];
        $objExport->resident_field_building_id = $aryData['resident_field_building_id'];
        $objExport->credit = $aryData['credit'];
        $objExport->reward_rating = $intRewardRating;
        $objExport->level = $intLevel;
        $objExport->reward_credit = $intRewardCredit;
        $objExport->position = $strPosition;
        
        return $objExport;
    }
    public static function verify_enableCommunityBuilding($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        
        $blnAvailable = true;
        $blnMoneyEnough = false;
        
        $objCurrentResident = db_resident::get_handle();
        $objNeighbourhood = new db_neighbourhood();
        $intNeighbourhoodId = $objNeighbourhood->retrieveId();
        
        //1. user has to have property on map
        $aryNeighbourhoodInfoList = db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->retrieveId());
        
        if(isset($aryNeighbourhoodInfoList[$intNeighbourhoodId]) && is_array(($aryNeighbourhoodInfoList[$intNeighbourhoodId]['map_item_list'])) && sizeof($aryNeighbourhoodInfoList[$intNeighbourhoodId]['map_item_list'])){
            $aryExportData['neighbourhood_id'] = $intNeighbourhoodId;
        }else{
            $aryMsg['Neighbourhood'] = db_language::get('inactive_resident');
            $blnAvailable = false;
        }
        
        $objMapItem = new db_mapmatrix();
        $objMapItem->setId($objData->map_item_id);
        $objMapItem->retrieve();
        
        if($objMapItem->map_element_type_id != 1){
            $aryMsg['MapItem'] = db_language::get('inactive_resident_for_enable_community_property');
            $blnAvailable = false;
        }
        
        if($blnAvailable && $objMapItem->neighbourhood_id != $intNeighbourhoodId){
            $aryMsg['Neighbourhood'] = db_language::get('no_community_land');
            $blnAvailable = false;
        }
        
        if($blnAvailable){
            $aryResidentFieldBuildingCondition = array();
            $aryResidentFieldBuildingCondition['map_matrix_id'] = $objMapItem->retrieveId();
            $aryResidentFieldBuildingCondition['id'] = $objData->resident_field_building_id;
            
            $aryResidentFieldBuildingSelect = array('id', 'map_matrix_id', 'resident_element_id', 'enabled');
            $aryResidentFieldBuilding = db_resident_field_building::retrieveList(db_resident_field_building::strTable, $aryResidentFieldBuildingCondition, 0, 0, $aryResidentFieldBuildingSelect);
            
            $aryResidentElementCondition = array();
            $aryResidentElementCondition['id'] = $aryResidentFieldBuilding['resident_element_id'];
            $aryResidentElementSelect = array('id', 'name', 'resident_element_type_id', 'credit');
            $aryResidentElement = db_resident_field_building::retrieveList(db_resident_field_building::strResidentElementTable, $aryResidentElementCondition, 0, 0, $aryResidentElementSelect);
            
            if(!is_array($aryResidentFieldBuilding) || sizeof($aryResidentFieldBuilding) < 1){
                $aryMsg['ResidentFieldBuilding'] = db_language::get('community_property_unexist');
                $blnAvailable = false;
            }else if($aryResidentElement['resident_element_type_id']%10!=1){
                $aryMsg['ResidentElement'] = db_language::get('resident_element_not_for_public');
                $blnAvailable = false;
            }else if($aryResidentElement['credit'] < 0){
                $aryMsg['ResidentFieldBuilding'] = db_language::get('resident_element_not_for_enable');
                $blnAvailable = false;
            }else if($aryResidentFieldBuilding['enabled']){
                $aryMsg['ResidentFieldBuilding'] = db_language::get('community_property_enabled_already');
                $blnAvailable = false;
            }else{
                $aryExportData['resident_field_building_id'] = $aryResidentFieldBuilding['id'];
                $aryExportData['map_matrix_id'] = $aryResidentFieldBuilding['map_matrix_id'];
                $aryExportData['resident_element_id'] = $aryResidentElement['id'];
                $aryExportData['credit'] = $aryResidentElement['credit'];
                
                //1. Check credit
                $intCreditReal = db_resident::retrieveFieldByCondition(db_resident::strTable, $objCurrentResident->facebook_id, 'facebook_id', 'credit');
                if($intCreditReal >= $aryResidentElement['credit']){
                    $blnMoneyEnough = true;
                }else{
                    $aryMsg['credit'] = db_language::get('not_enough_credit');
                }
                
            }
        }
        
        return array($blnMoneyEnough&$blnAvailable, $aryMsg, $aryExportData);
    }
    /**
     * Input:
     * objData.map_item_id = int <-- map item id
     * objData.resident_field_building_id = int <-- resident field_building id
     * objData.element_task_id = int <-- element task id
     * 
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.resident_field_building_id = int <-- resident field_building id
     * objExport.element_task_id = int <-- element task id
     * objExport.msg = string <-- error msg or how much user earn
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     * Example: 6.php
     **/
    public static $_doBuildingTask = 'ActionResponseBuildingTask';
    public static function doBuildingTask($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        
        $objResidentTaskHistory = new db_resident_task_history();
        $objResidentTaskHistory->neighbourhood_id = $aryData['neighbourhood_id'];
        $objResidentTaskHistory->map_matrix_id = $aryData['map_matrix_id'];
        $objResidentTaskHistory->resident_element_id = $aryData['resident_element_id'];
        $objResidentTaskHistory->owner_resident_id = $aryData['owner_resident_id'];
        $objResidentTaskHistory->resident_field_building_id = $aryData['resident_field_building_id'];
        $objResidentTaskHistory->element_task_id = $aryData['element_task_id'];
        $objResidentTaskHistory->resident_id = $objCurrentResident->id;
        $objResidentTaskHistory->resident_name = $objCurrentResident->name;
        $objResidentTaskHistory->rating = $aryData['rating'] ? db_task::retrieveFieldByCondition(db_task::strElementTaskTable, $aryData['element_task_id'], 'id', 'rating') : 0;
        $objResidentTaskHistory->timestamp = date('Y-m-d H:i:s');
        $objResidentTaskHistory->doBuildingTask();
        
        db_task::retrieveTaskListForOneBuilding($objResidentTaskHistory->resident_field_building_id, true);
        db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->id, true);
        
        $objExport = self::_packageObject(self::$_doBuildingTask);
        $objExport->resident_field_building_id = $aryData['resident_field_building_id'];
        $objExport->element_task_id = $aryData['element_task_id'];
        $objExport->map_matrix_id = $aryData['map_matrix_id'];
        
        return $objExport;
    }
    public static function verify_doBuildingTask($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $aryExportData['rating'] = true;
        $aryExportData['neighbourhood_id'] = 0;
        $aryExportData['map_matrix_id'] = 0;
        $aryExportData['resident_element_id'] = 0;
        $aryExportData['owner_resident_id'] = 0;
        $aryExportData['resident_field_building_id'] = 0;
        $aryExportData['element_task_id'] = 0;
        
        $objCurrentResident = db_resident::get_handle();
        
        $objNeighbourhood = new db_neighbourhood();
        $intNeighbourhoodId = $objNeighbourhood->retrieveId();
        
        //1. user has to have property on map
        $aryNeighbourhoodInfoList = db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->retrieveId());
        
        $blnAvailable = false; 
        if(isset($aryNeighbourhoodInfoList[$intNeighbourhoodId]) && is_array(($aryNeighbourhoodInfoList[$intNeighbourhoodId]['map_item_list'])) && sizeof($aryNeighbourhoodInfoList[$intNeighbourhoodId]['map_item_list'])){
            $blnAvailable = true;
            $aryExportData['neighbourhood_id'] = $intNeighbourhoodId;
        }else{
            $aryMsg['Neighbourhood'] = db_language::get('inactive_resident_for_task');
        }
        
        if($blnAvailable){
            //2. user still has not over task limitation
            if(!db_resident_task_history::countAvailableTaskForResidentInNeighbourhood($objCurrentResident->id, $intNeighbourhoodId)){
                $aryMsg['Neighbourhood'] = db_language::get('no_more_task');
            }else{
                $blnAvailable = true;
            }
            if($blnAvailable){
                //3. Is map item exist and enabled and is self property
                $aryMapItemCondition = array();
                $aryMapItemCondition['id'] = $objData->map_item_id;
                $aryMapItemCondition['neighbourhood_id'] = $intNeighbourhoodId;
                
                $aryMapItem = db_mapmatrix::retrieveList(db_mapmatrix::strTable, $aryMapItemCondition, 0, 0, array(
                                                    'id',
                                                    'neighbourhood_id',
                                                    'map_element_type_id',
                                                    'resident_id',));
                
                $blnAvailable = false; 
                if(!is_array($aryMapItem) || sizeof($aryMapItem) < 1){
                    $aryMsg['MapItem'] = db_language::get('land_unexist');
                }elseif($aryMapItem['map_element_type_id'] == 2){
                    $aryMsg['MapItem'] = 'land has no task';
                }else{
                    $blnAvailable = true;
                    $aryExportData['map_matrix_id'] = $aryMapItem['id'];
                     $aryExportData['owner_resident_id'] = $aryMapItem['resident_id'];
                }
                
                if($aryMapItem['map_element_type_id'] == 3 && $aryMapItem['resident_id'] == $objCurrentResident->id){
                    $aryExportData['rating'] = false;
                }
                
                if($blnAvailable){
                    //4. Is building exist
                    $aryResidentFieldBuildingCondition = array();
                    $aryResidentFieldBuildingCondition['id'] = $objData->resident_field_building_id;
                    $aryResidentFieldBuildingCondition['map_matrix_id'] = $aryMapItem['id'];
                    
                    $aryResidentFieldBuilding = db_resident_field_building::retrieveList(db_resident_field_building::strTable, $aryResidentFieldBuildingCondition, 0, 0, array(
                                                        'id',
                                                        'map_matrix_id',
                                                        'enabled',
                                                        'resident_element_id'));
                    
                    $blnAvailable = false;
                    if(!is_array($aryResidentFieldBuilding) || sizeof($aryResidentFieldBuilding) < 1){
                        $aryMsg['ResidentFieldBuilding'] = db_language::get('task_unexist');
                    }elseif(!$aryResidentFieldBuilding['enabled']){
                        $aryMsg['ResidentFieldBuilding'] = db_language::get('task_unavailable');
                    }else{
                        $blnAvailable = true;
                        $aryExportData['resident_field_building_id'] = $aryResidentFieldBuilding['id'];
                        $aryExportData['resident_element_id'] = $aryResidentFieldBuilding['resident_element_id'];
                    }
                    
                    if($blnAvailable){
                        //5. Is task exist 
                        $blnAvailable = false;
                        $aryBuildingTaskStatusCondition = array();
                        $aryBuildingTaskStatusCondition['resident_field_building_id'] = $objData->resident_field_building_id;
                        $aryBuildingTaskStatusCondition['element_task_id'] = $objData->element_task_id;
                        
                        $aryBuildingTaskStatus = db_task::retrieveList(db_task::strBuildingTaskStatusTable, $aryBuildingTaskStatusCondition, 0, 0, array(
                                                            'resident_field_building_id',
                                                            'element_task_id',
                                                            'timestamp',
                                                            'last_finish'));
                        
                        $blnResidentFieldBuildingAvailable = false;
                        if(!is_array($aryBuildingTaskStatus) || sizeof($aryBuildingTaskStatus) < 1){
                            $aryMsg['BuildingTaskStatus'] = db_language::get('task_unexist');
                        }elseif(strtotime($aryBuildingTaskStatus['timestamp']) <= strtotime($aryBuildingTaskStatus['last_finish'])){
                            $aryMsg['BuildingTaskStatus'] = db_language::get('task_already_done');
                        }elseif(strtotime($aryBuildingTaskStatus['timestamp']) > strtotime('now') ){
                            $aryMsg['BuildingTaskStatus'] = db_language::get('task_not_start');
                        }else{
                            $blnAvailable = true;
                            $aryExportData['element_task_id'] = $aryBuildingTaskStatus['element_task_id'];
                        }
                    }
                }
                
            }
        }
        return array($blnAvailable, $aryMsg, $aryExportData);
    }
    
    /**
     * Input:
     * objData.map_item_id = int <-- map_item_id
     * objData.facebook_id = int <-- map_item_id
     * 
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.id = int <-- map_item_id
     * objExport.msg = string <-- error msg or how much user can keep
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     * Example: 10.php
     **/
    public static $_reserveMapItem = 'ActionResponseMapItem';
    public static function reserveMapItem($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        
        $objMapItem = new db_mapmatrix();
        $objMapItem->id = $aryData['map_item_id'];
        $objMapItem->resident_id = $objCurrentResident->id;
        $objMapItem->reserve_to_facebook_id = trim($aryData['facebook_id']);
        $objMapItem->neighbourhood_id = $aryData['neighbourhood_id'];
        $objMapItem->reserveMapItem();
        
        db_mapmatrix::retrieveOneMapMatrix($aryData['map_item_id'], true);
        
        $intResidentId = db_resident::retrieveFieldByCondition(db_resident::strTable, $aryData['facebook_id'], 'facebook_id', 'id');
        if($intResidentId > 1000){
            db_neighbourhood::retrieveNeighbourhoodInfoForResident($aryData['facebook_id'], $intResidentId, true);
        }
        
        $objExport = self::_packageObject(self::$_reserveMapItem);
        $objExport->map_item_id = $aryData['map_item_id'];
        $objExport->facebook_id = $aryData['facebook_id'];
        return $objExport;
    }
    public static function verify_reserveMapItem($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendList = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        
        $blnPass = false;
        if(!trim($objData->facebook_id)){// || !$aryFacebookFriendList[$objData->facebook_id]
            $aryMsg['FriendInfo'] = 'not friends, permission denied';
        }else{
            $aryExportData['facebook_id'] = $objData->facebook_id;
            $blnPass = true;
        }
        
        $objMapItem = new db_mapmatrix();
        $objMapItem->setId($objData->map_item_id);
        $objMapItem->name = $objData->name;
        
        $objNeighbourhood = new db_neighbourhood();
        $intNeighbourhoodId = $objNeighbourhood->retrieveId();
        
        $aryCondition = array();
        $aryCondition['id'] = $objMapItem->retrieveId();
        $aryCondition['neighbourhood_id'] = $intNeighbourhoodId;
        $aryMapItem = db_mapmatrix::retrieveList(db_mapmatrix::strTable, $aryCondition, 0, 0, array(
                                            'id',
                                            'neighbourhood_id',
                                            'x',
                                            'y',
                                            'map_element_type_id',
                                            'resident_id',
                                            'reserve_to_facebook_id',
                                            'timestamp',
                                            'name',
                                            'credit',
                                            'credit_for_sold',));
        
        $blnAvailable = false; 
        $blnReserved = $aryMapItem['reserve_to_facebook_id'] && strtotime($aryMapItem['timestamp']) >= strtotime('now') ? true : false;
        if(!is_array($aryMapItem) || sizeof($aryMapItem) < 1){
            $aryMsg['MapItem'] = db_language::get('land_unexist');
        }elseif($aryMapItem['map_element_type_id'] == 1){
            $aryMsg['MapItem'] = db_language::get('land_not_for_sale');
        }elseif($aryMapItem['map_element_type_id'] == 3){
            $aryMsg['MapItem'] = db_language::get('land_sold_already');
        }elseif($blnReserved && $aryMapItem['map_element_type_id'] == 2 && $aryMapItem['reserve_to_facebook_id']){
            $aryMsg['MapItem'] = db_language::get('land_reserved');
        }elseif($aryMapItem['map_element_type_id'] == 2 && !$blnReserved){
            $blnAvailable = true;
        }
        
        //1. user has to have property on map
        $aryNeighbourhoodInfoList = db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->retrieveId());
        
        $blnPermission = false; 
        if(isset($aryNeighbourhoodInfoList[$intNeighbourhoodId]) && is_array(($aryNeighbourhoodInfoList[$intNeighbourhoodId]['map_item_list'])) && sizeof($aryNeighbourhoodInfoList[$intNeighbourhoodId]['map_item_list'])){
            $blnPermission = true;
            $aryExportData['neighbourhood_id'] = $intNeighbourhoodId;
        }else{
            $aryMsg['Neighbourhood'] = db_language::get('inactive_resident_for_reserve_land');
        }
        
        $aryExportData['map_item_id'] = $objMapItem->retrieveId();
        
        
        return array($blnPass&$blnAvailable&$blnPermission, $aryMsg, $aryExportData);
    }
    
    /**
     * Input:
     * objData.map_item_id = int <-- map item id
     * objData.name = string <-- map_item_name
     * 
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.map_item_id = int <-- map item id
     * objExport.msg = string <-- error msg or how much user earn
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     * Example: 11.php
     **/
    public static $_nameMapItem = 'ActionResponseMapItem';
    public static function nameMapItem($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        $objMapItem = new db_mapmatrix();
        $objMapItem->setId($aryData['map_item_id']);
        $objMapItem->name = $aryData['name'];
        $objMapItem->nameMapItem();
        
        db_mapmatrix::retrieveOneMapMatrix($objMapItem->retrieveId(), true);
        db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->id, true);
        
        $objExport = self::_packageObject(self::$_buyMapItem);
        $objExport->map_item_id = $aryData['map_item_id'];
        return $objExport;
    }
    public static function verify_nameMapItem($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        
        $blnAvailable = true;
        
        $objCurrentResident = db_resident::get_handle();
        $objNeighbourhood = new db_neighbourhood();
        
        $objMapItem = new db_mapmatrix();
        $objMapItem->setId($objData->map_item_id);
        $objMapItem->retrieve();
        
        if($objMapItem->map_element_type_id != 3 || $objMapItem->resident_id != $objCurrentResident->id){
            $aryMsg['MapItem'] = db_language::get('land_not_yours');
            $blnAvailable = false;
        }
        
        if($objMapItem->neighbourhood_id != $objNeighbourhood->retrieveId()){
            $aryMsg['Neighbourhood'] = db_language::get('land_unexist');
            $blnAvailable = false;
        }
        
        $objData->name = trim(clear_html_string($objData->name));
        if(!$objData->name){
            $aryMsg['name'] = db_language::get('land_name_required');
            $blnAvailable = false;
        }else if(fnProfanity($objData->name)){
            $aryMsg['name'] = db_language::get('profanity_contents');
            $blnAvailable = false;
        }else{
            $aryExportData['name'] = $objData->name;
        }
        $aryExportData['map_item_id'] = $objMapItem->retrieveId();
        
        return array($blnAvailable, $aryMsg, $aryExportData);
    }
    
    /**
     * Input:
     * objData.notification = int <-- notification 1/0 on/off
     * 
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.notification = int <-- notification 1/0 on/off
     * objExport.msg = string <-- error msg or how much user earn
     * 
     * Example: g.php
     **/
    public static $_setNotification = 'ActionResponseNotification';
    public static function setNotification($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        $objCurrentResident->notification = $aryData['notification'];
        $objCurrentResident->updateNotification();
        
        $objExport = self::_packageObject(self::$_setNotification);
        $objExport->notification = $aryData['notification'];
        return $objExport;
    }
    public static function verify_setNotification($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        
        $blnAvailable = true;
        
        if($objData->notification == 1 || $objData->notification == 0){
            $aryExportData['notification'] = (int)$objData->notification;
        }else{
            $aryMsg['notification'] = 'Data out of range';
            $blnAvailable = false;
        }
        
        return array($blnAvailable, $aryMsg, $aryExportData);
    }
    
    /**
     * confirm read message
     * Input:
     * id[] = int[] <-- int array for message id 1,2,3,4 
     * 
     * Example: a1.php
     **/
    public static $_confirmReadMessage = 'ActionResponseMessage';
    public static function confirmReadMessage($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        message_box::readed($objCurrentResident->id, $aryData);
        
        $objExport = self::_packageObject(self::$_confirmReadMessage);
        return $objExport;
    }
    public static function verify_confirmReadMessage($aryData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        if(!is_array($aryData)){
            $aryMsg['msg'] = 'data format error';
            $blnPass = false;
        }else{
            foreach($aryData as $intData){
                if($intData > 0){
                    $aryExportData[] = $intData;
                }else{
                    $blnPass = false;
                    break;
                }
            }
        }
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    /**
     * Input: string[][,]
     * 
     * Export: 
     * objEmailToFriends = object
     * objEmailToFriends.msg = string[] //Example format: bjEmailToFriends.msg[0] = "email,,2,,format error";
     * objEmailToFriends.success = bool //true|false
     * objEmailToFriends.ary_referral_list = string[][,] //Example format: bjEmailToFriends.ary_referral_list[0] = ["email@example.net","Example name"];
     * 
     * Example: h.php
     **/
    public static $_sendEmailToFriends = 'ActionResponseEmailLog';
    public static function sendEmailToFriends($aryReferralList)
    {
        $objCurrentResident = db_resident::get_handle();
        
        $blnEmail = false;
        $aryEmailList = array();
        $aryEmailStatus = array();
        $aryEmailNameStatus = array();
    
        if(is_array($aryReferralList)){
            
            $aryEmailListTmp = array();
            $aryEmailNameListTmp = array();
            foreach($aryReferralList as $intPos => $aryEmailSetting){
                
                $strEmail = trim($aryEmailSetting[0]);
                $strName = trim($aryEmailSetting[1]);
                $aryEmailStatus[$intPos] = false;
                $aryEmailNameStatus[$intPos] = false;
                if(fnEmailCheck($strEmail)){
                    $aryEmailStatus[$intPos] = true;
                }
                if(strlen($strName) > 0 && strlen($strName) < 250){
                    $aryEmailNameStatus[$intPos] = true;
                }
                if($aryEmailStatus[$intPos] && $aryEmailNameStatus[$intPos]){
                    $aryEmailList[$intPos] = array($strEmail, $strName);
                }else{
                    if(!$aryEmailStatus[$intPos]){
                        message_queue::push('error', db_language::get('referral_email_error', array('intPos'=>$intPos)));
                    }
                    if(!$aryEmailNameStatus[$intPos]){
                        message_queue::push('error', db_language::get('referral_name_error', array('intPos'=>$intPos)));
                    }
                }
            }
            
            if(sizeof($aryEmailList) > 0 && sizeof($aryEmailList) <= 5){
                //All pass validate
                foreach ($aryEmailList as $aryEmailSetting){
                    $objEmailLog = new email_log();
                    $objEmailLog->register_id        = $objCurrentResident->id;
                    $objEmailLog->refer_email        = $aryEmailSetting[0];
                    $objEmailLog->refer_email_name    = $aryEmailSetting[1];
                    $objEmailLog->aryRegister        = array('register_id'        => $objCurrentResident->id,
                                                            'register_name'        => $objCurrentResident->name,
                                                            'register_email'    => $objCurrentResident->email,
                                                            );
                    $objEmailLog->submitEmail();
                }
            }else{
                message_queue::push('error', db_language::get('referral_all_error'));
            }
        }else{
            message_queue::push('error', db_language::get('referral_all_error'));
        }
        
        $objExport = self::_packageObject(self::$_sendEmailToFriends);
        $objExport->ary_referral_list = $aryEmailList;
        return $objExport;
    }
    public static function verify_sendEmailToFriends($aryReferralList)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryReferralList);
    }
    
    /**
     * Input:
     * objCuppon.cuppon_code = string
     * 
     * Export: 
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.credit = int <-- how much user get
     * objExport.msg = string <-- error msg or how much user spend
     * 
     * Example: i.php
     **/
    public static $_exchangeCuppon = 'ActionResponseExchangeCuppon';
    public static function exchangeCuppon($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        $objCupponResidentHistory = new db_cuppon_resident_history();
        $objCupponResidentHistory->cuppon_code = $aryData['cuppon_code'];
        $objCupponResidentHistory->exchangeCupponForCredit($objCurrentResident);
        
        db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->id, true);
        
        $objExport = self::_packageObject(self::$_exchangeCuppon);
        $objExport->credit = $objCupponResidentHistory->credit;
        return $objExport;
    }
    public static function verify_exchangeCuppon($objData)
    {
        $objCurrentResident = db_resident::get_handle();
        
        $aryMsg = array();
        $aryExportData = array();
        $aryExportData['_explicitType'] = self::$_exchangeCuppon;
        
        //1. verify cuppon code
        $aryCupponCondition = array();
        $aryCupponCondition['cuppon_code'] = $objData->cuppon_code;
        $aryCupponSelect = array('id', 'cuppon_code', 'credit', 'available_qty', 'start_date', 'end_date');
        $aryCuppon = db_cuppon_resident_history::retrieveList(db_cuppon_resident_history::strCupponTable, $aryCupponCondition, 1, 0, $aryCupponSelect);
        
        $blnPass = false;
        if(is_array($aryCuppon) && sizeof($aryCuppon)){
            $tmStartDate = strtotime($aryCuppon['start_date']);
            $tmEndDate = strtotime($aryCuppon['end_date']);
            $tmNow = strtotime('now');
            if($aryCuppon['available_qty'] > 0 && ($tmStartDate <= $tmNow || $tmNow <= $tmEndDate)){
                $aryCupponResidentHistoryCondition = array();
                $aryCupponResidentHistoryCondition['resident_id'] = $objCurrentResident->id;
                $aryCupponResidentHistoryCondition['cuppon_id'] = $aryCuppon['id'];
                $aryCupponResidentHistorySelect = array('resident_id', 'cuppon_id');
                $aryCupponResidentHistory = db_cuppon_resident_history::retrieveList(db_cuppon_resident_history::strTable, $aryCupponResidentHistoryCondition, 1, 0, $aryCupponResidentHistorySelect);
                
                if(is_array($aryCupponResidentHistory) && sizeof($aryCupponResidentHistory)){
                    $aryMsg['cuppon_code'] = db_language::get('coupon_used');
                }else{
                    $aryExportData['cuppon_code'] = $aryCuppon['cuppon_code'];
                    $blnPass = true;
                }
            }else if($aryCuppon['available_qty'] < 1){
                $aryMsg['cuppon_code'] = db_language::get('coupon_unavailable');
            }else if($tmStartDate > $tmNow){
                $aryMsg['cuppon_code'] = db_language::get('coupon_unavailable_yet');
            }else if($tmNow > $tmEndDate){
                $aryMsg['cuppon_code'] = db_language::get('coupon_expired');
            }
        }else{
            $aryMsg['cuppon_code'] = db_language::get('coupon_unexist');
        }
        return array($blnPass, $aryMsg, $aryExportData);
    }


    
    /**
     * Input:
     * objOrder.FirstName = string
     * objOrder.LastName = string
     * objOrder.Email = string
     * objOrder.Address = string
     * objOrder.Postcode = string
     * objOrder.CardHoldersName = string
     * objOrder.CardNumber = string
     * objOrder.CardExpiryMonth = string
     * objOrder.CardExpiryYear = string
     * objOrder.PlanCode = 5,10,20,50,100
     *                    $5   => 500 credits,
     *                    $10  => 1050 credits,
     *                    $20  => 2150 credits,
     *                    $50  => 5500 credits,
     *                    $100 => 12000 credits
     * 
     * Export: 
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.msg = string <-- error msg or how much user spend
     * 
     * Example: j.php
     **/
    public static $_placeDonate = 'ActionResponsePlaceDonate';
    public static function placeDonate($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        
        $objOrder = new db_order();
        $objOrder->FirstName        = $aryData['FirstName'];
        $objOrder->LastName         = $aryData['LastName'];
        $objOrder->Email            = $aryData['Email'];
        
        $objOrder->Address          = $aryData['Address'];
        $objOrder->Postcode         = $aryData['Postcode'];
        
        $objOrder->CardHoldersName  = $aryData['CardHoldersName'];
        $objOrder->CardNumber       = $aryData['CardNumber'];
        $objOrder->CardExpiryMonth  = $aryData['CardExpiryMonth'];
        $objOrder->CardExpiryYear   = $aryData['CardExpiryYear'];
        
        $objOrder->PlanCode         = $aryData['PlanCode'];
        
        $objOrder->resident_id      = $objCurrentResident->id;
        $objOrder->facebook_id      = $objCurrentResident->facebook_id;
        $objOrder->trans_no         = uniqid();
        
        $objOrder->objRegister      = $objCurrentResident;
         
        $objOrder->placeDonate();
        
        db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->id, true);
        
        $objExport = self::_packageObject(self::$_placeDonate);
        return $objExport;
    }
    public static function log_placeDonate($objData){
        $objExportData = new stdClass();
        $objExportData->FirstName         = $objData->FirstName;
        $objExportData->LastName          = $objData->LastName;
        $objExportData->Email             = $objData->Email;
        
        $objExportData->Address           = $objData->Address;
        $objExportData->Postcode          = $objData->Postcode;
        
        $objExportData->PlanCode          = $objData->PlanCode;
        
        return $objExportData;
    }
    public static function verify_placeDonate($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        
        $aryExport['FirstName']         = $objData->FirstName;
        $aryExport['LastName']          = $objData->LastName;
        $aryExport['Email']             = $objData->Email;
        
        $aryExport['Address']           = $objData->Address;
        $aryExport['Postcode']          = $objData->Postcode;
        
        $aryExport['CardHoldersName']   = $objData->CardHoldersName;
        $aryExport['CardNumber']        = $objData->CardNumber;
        $aryExport['CardExpiryMonth']   = (int)$objData->CardExpiryMonth;
        $aryExport['CardExpiryYear']    = (int)$objData->CardExpiryYear;
        
        $aryExport['PlanCode']          = $objData->PlanCode;
        
        if(empty($aryExport['FirstName']) || strlen($aryExport['FirstName']) < 1 || strlen($aryExport['FirstName']) > 250){
            $aryMsg['FirstName'] = db_language::get('order_first_name_error');
        }else{
            $aryExport['FirstName'] = trim($aryExport['FirstName']);
        }
        
        if(empty($aryExport['LastName']) || strlen($aryExport['LastName']) < 1 || strlen($aryExport['LastName']) > 250){
            $aryMsg['LastName'] = db_language::get('order_last_name_error');
        }else{
            $aryExport['LastName'] = trim($aryExport['LastName']);
        }
        
        $aryExport['Email'] = trim($aryExport['Email']);
        if(!fnEmailCheck($aryExport['Email'])){
            $aryMsg['Email'] = db_language::get('order_email_error');
        }
        
        if(empty($aryExport['Address']) || strlen($aryExport['Address']) < 1 || strlen($aryExport['Address']) > 8000){
            $aryMsg['Address'] = db_language::get('order_address_error');
        }else{
            $aryExport['Address'] = trim($aryExport['Address']);
        }
        
        if(empty($aryExport['Postcode']) || strlen($aryExport['Postcode']) < 1 || strlen($aryExport['Postcode']) > 250){
            $aryMsg['Postcode'] = db_language::get('order_postcode_error');
        }else{
            $aryExport['Postcode'] = trim($aryExport['Postcode']);
        }
        
        if(empty($aryExport['CardHoldersName']) || strlen($aryExport['CardHoldersName']) < 1 || strlen($aryExport['CardHoldersName']) > 250){
            $aryMsg['CardHoldersName'] = db_language::get('order_card_name_error');
        }else{
            $aryExport['CardHoldersName'] = trim($aryExport['CardHoldersName']);
        }
        
        if(empty($aryExport['CardNumber']) || strlen($aryExport['CardNumber']) < 1 || strlen($aryExport['CardNumber']) > 250){
            $aryMsg['CardNumber'] = db_language::get('order_card_number_error');
        }else{
            $aryExport['CardNumber'] = trim($aryExport['CardNumber']);
        }
        
        if(empty($aryExport['CardExpiryMonth']) || $aryExport['CardExpiryMonth'] < 1 || $aryExport['CardExpiryMonth'] > 12){
            $aryMsg['CardExpiryMonth'] = db_language::get('order_card_expire_month_error');
        }
        
        $tmExpire = strtotime($aryExport['CardExpiryYear'].'-'.$aryExport['CardExpiryMonth'].'-01 +1 month');
        if($tmExpire <= strtotime(date('Y-m-01'))){
            $aryMsg['CardExpiryYear'] = db_language::get('order_card_expire_year_error');
        }
        
        if(!isset(db_order::$aryCreditsPackList[$aryExport['PlanCode']])){
            $aryMsg['PlanCode'] = db_language::get('order_pack_unexist');
        }
        return array(sizeof($aryMsg) ? false : true, $aryMsg, $aryExport);
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////    
//////////                         //////////////////////////////////////////////////////////////////////    
//////////  game related           //////////////////////////////////////////////////////////////////////    
/////////                          //////////////////////////////////////////////////////////////////////    
/////////////////////////////////////////////////////////////////////////////////////////////////////////    
        
    
  /**
     * @param $objData 
     *             $objData->neighbourhood_id
     *             $objData ->$objData->game_log_id
     *             
     * @return  [
     *                     status:"successful",
     *                     message:"",
     *                     result : object of com.miniGame.startGames{point: 5, game_log_id : 22, neighbourhood_id: 15, resident_id:6} 
     *             ]        ;
     *         or  failed
     *            1>    [status:"failed",message:"invalid game log id"];
     *             2>  [status:"failed",message:"finished outside time range"];
     **/
    public static $_endGame = 'endGame';
    public static function endGame($objData)
    {
        $objData->cmd = 'end_game';
        list($blnSuccess, $aryMsg, $objMiniGame) = games::dispatch($objData);
        //clear cache
        $aryTaskSettingsList = db_resident::retrieveNeighbourhoodGameInfoForResident($player[facebook_id], $player[neighbourhood_id], true);
        
        $objExport = self::_packageObject(self::$_endGame);
        $objExport->_explicitType = $objMiniGame['_explicitType'];
        $objExport->success = $blnSuccess;
        $objExport->point = $objMiniGame['point'];
        $objExport->game_log_id = $objMiniGame['game_log_id'];
        $objExport->neighbourhood_id = $objMiniGame['neighbourhood_id'];
        $objExport->resident_id = $objMiniGame['resident_id'];
        
        return $objExport;
    }
    public static function verify_endGame($objData)
    {
        $aryMsg = array();
        $aryExportData = $objData;
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
     /**
     * @param $objData 
     *             $objData->neighbourhood_id
     *             $objData ->$objData->game_id
     *             
     * @return [
     *                     status:"successful",
     *                     message:"",
     *                     result :object of com.miniGame.startGames object{game_log_id : 2,neighbourhood_id: 13,resident_id:7} 
     *             ]        
     *         or  failed
     *         1>    [status:"failed",message:"no more game to play"];
     *         2>    [status:"failed",message:"invalid game id"];
     **/
    public static $_startGame = 'startGame';
    public static function startGame($aryData)
    {
        $objData = new stdClass();
        $objData->neighbourhood_id = $aryData['neighbourhood_id'];
        $objData->game_id = $aryData['game_id'];
        $objData->cmd = 'start_game';
        $objExport = games::dispatch($objData);
        return $objExport;
    }
    
    public static function verify_startGame($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $aryExportData['game_id'] = (int)$objData->game_id;
        
        $objCurrentResident = db_resident::get_handle();
        
        $objNeighbourhood = new db_neighbourhood();
        $intNeighbourhoodId = $objNeighbourhood->retrieveId();
        
        //1. user has to have property on map
        $aryNeighbourhoodInfoList = db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->retrieveId());
        
        $blnPass = false;
        if(isset($aryNeighbourhoodInfoList[$intNeighbourhoodId]) && is_array(($aryNeighbourhoodInfoList[$intNeighbourhoodId]['map_item_list'])) && sizeof($aryNeighbourhoodInfoList[$intNeighbourhoodId]['map_item_list'])){
            $blnPass = true;
            $aryExportData['neighbourhood_id'] = $intNeighbourhoodId;
            if($aryExportData['neighbourhood_id'] != $intNeighbourhoodId){
                $aryMsg['Neighbourhood'] = db_language::get('not_current_neighbourhood');
                $blnPass = false; 
            }
        }else{
            $aryMsg['Neighbourhood'] = db_language::get('inactive_resident_for_play_game');
        }
        
        return array($blnPass, $aryMsg, $aryExportData);
    }

     /**
     * @param $objData 
     *             $objData->resident_id
     *             $objData->neighbourhood_id
     *             
     * @return [ 
     *                     status:"successful",
     *                     message:"",
     *                     result :object of com.miniGame.allAvailableGames object{total : 3 } 
     *             ]        
     *         or  failed
     *         1>    [status:"failed",message:"no more game to play"];
     **/
    public static $_availableGame = 'availableGame';
    public static function availableGame()
    {
        $objCurrentResident = db_resident::get_handle();
        $objNeighbourhood = new db_neighbourhood();
        
        $objData = new stdClass();
        $objData->resident_id = $objCurrentResident->id;
        $objData->neighbourhood_id = $objNeighbourhood->retrieveId();
        
        $objData->cmd = 'get_availible_games';
        $objExport = games::dispatch($objData);
        return $objExport;
    }
    
    public static function verify_availableGame($objData)
    {
        $aryMsg = array();
        $aryExportData = $objData;
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
        
    
    
    
    /**
     * Input:
     * objData.id = int <-- map_item_id
     * objData.resident_element_id = int <-- resident element id
     * 
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.id = int <-- map_item_id
     * objExport.resident_element_id = int <-- resident element id
     * objExport.credit = int <-- how much user get
     * objExport.msg = string <-- error msg or how much user spend
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     **/
    public static $_sellBuildingItem = 'ActionResponseBuildingItem';
    public static function sellBuildingItem($objData)
    {
        //1. Check credit
        resident::retrieveFieldByCondition();
        //2. Update map resident id
        //3. Update resident credit
        return $objExport;
    }
    public static function verify_sellBuildingItem($objData)
    {
        return $objExport;
    }
    
    /**
     * Input:
     * objData.id = int <-- map_item_id
     * 
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.id = int <-- map_item_id
     * objExport.msg = string <-- error msg or how much user earn
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     **/
    public static $_maintainMapItem = 'ActionResponseMapItem';
    public static function maintainMapItem($objData)
    {
        //call self::doBuildingTask()
        return $objExport;
    }
    public static function verify_maintainMapItem($objData)
    {
        return $objExport;
    }
    
    /**
     * Input:
     * objData.id = int <-- map_item_id
     * 
     * Output
     * objExport = (object)
     * objExport.success = bool //true|false
     * objExport.id = int <-- map_item_id
     * objExport.msg = string
     * 
     * After retrieve result, if success is true
     * Please call:
     * service_info::retrieveMapItem(objData)
     * service_info::retrievePersonalInfo()
     * to refresh map item info and user self info
     * 
     **/
    public static $_bulldozeMapItem = 'ActionResponseMapItem';
    public static function bulldozeMapItem($objData)
    {
        //call self::doBuildingTask()
        return $objExport;
    }
    public static function verify_bulldozeMapItem($objData)
    {
        return $objExport;
    }
    
    /**
     * Require schedule credits
     * 
     * 
     * 
     * 
     **/
    public static $_requireScheduleCredits = 'ActionResponseScheduleCredits';
    public static function requireScheduleCredits()
    {
        
        return $objExport;
    }
    public static function verify_requireScheduleCredits($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
}


