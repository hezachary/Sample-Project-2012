<?php
class service_info
{
    public static $blnUseAdapter;
    public static $strPrivateMethod;
    public static function privateAdapter(){
        if(!self::$blnUseAdapter || !self::$strPrivateMethod || !method_exists('self',self::$strPrivateMethod)){
            return false;
        }
        $aryArgs = func_get_args();
        $data = call_user_func_array(array('self', self::$strPrivateMethod), $aryArgs);
        self::$blnUseAdapter = 0;
        self::$strPrivateMethod = null;
    }

    private static function _aryFontendOptions($lifetime = 60, $caching = true, $automatic_serialization = true, $automatic_cleaning_factor = true){
        return array(
                    'lifeTime'                    => $lifetime,
                    'caching'                    => $caching,
                    'automatic_serialization'    => $automatic_serialization,
                    'automatic_cleaning_factor'    => $automatic_cleaning_factor,
        );
    }
    
    private static function _aryBackendOptions(){
        global $CFG;
        return array('cacheDir' => $CFG->cache);
    }
    
    public static function _packageObject($strExplicitType, $aryFiledList, $aryDefine){
        $objExport = new stdClass();
        $objExport->_explicitType = $strExplicitType;
        foreach($aryDefine as $key => $value){
            $var = is_int($key) ? $value : $key;
            $objExport->$var = $aryFiledList[$value];
        }
        return $objExport;
    }
    
    /**
     * Retrieve all the id list
     * 
     * Output:
     * objNeighbourhood[] = (object)[]
     * objNeighbourhoodLis.id = int
     * 
     * Example: 1.php
     **/
    public static $__retrieveAllNeighbourhoodIdList = 'NeighbourhoodIdList';
    public static function retrieveAllNeighbourhoodIdList()
    {
        $aryNeighbourhoodListTmp = db_neighbourhood::retrieveAllNeighbourhoodId();
        
        $aryNeighbourhoodList = array();
        foreach($aryNeighbourhoodListTmp as $aryNeighbourhood){
            $aryNeighbourhoodList[] = self::_packageObject(self::$__retrieveNeighbourhood, $aryNeighbourhood, array('id'));
        }
        
        return $aryNeighbourhoodList;
    }
    public static function verify_retrieveAllNeighbourhoodIdList()
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    /**
     * Input:
     * objData.neighbourhood_list = objNeighbourhood[]
     * objNeighbourhood.id = int
     * 
     * Output:
     * objNeighbourhoodList = (object)
     * objNeighbourhoodLis.player_qty = int
     * objNeighbourhoodList.neighbourhood_list = objyNeighbourhood[]
     * 
     * objyNeighbourhood.id = int
     * objyNeighbourhood.name = string
     * objyNeighbourhood.level = int
     * objyNeighbourhood.rating = int
     * objyNeighbourhood.template_name = string //file_name
     * objyNeighbourhood.resident_qty = int
     * objyNeighbourhood.free_land_qty = int
     * objyNeighbourhood.facebook_friend_id_list = string[] 
     * 
     * Example: 2.php
     **/
    public static $__retrieveNeighbourhood = 'Neighbourhood';
    public static $__NeighbourhoodRegister = 'NeighbourhoodRegister';
    public static function retrieveNeighbourhoodList($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendList = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        
        $aryNeighbourhoodList = array();
        foreach($aryData['neighbourhood_list'] as $intNeighbourhoodId){
            $aryNeighbourhood = db_neighbourhood::retrieveOneNeighbourhood($intNeighbourhoodId);
            
            $objNeighbourhood = self::_packageObject(self::$__retrieveNeighbourhood, $aryNeighbourhood, array('id', 'name', 'rating', 'level','resident_qty', 'free_land_qty'));
            
            $objNeighbourhood->facebook_friend_id_list = array();
            $aryNeighbourhood['neighbourhood_resident_list'] = !is_array($aryNeighbourhood['neighbourhood_resident_list']) ? array() : $aryNeighbourhood['neighbourhood_resident_list'];
            foreach($aryNeighbourhood['neighbourhood_resident_list'] as $aryResident){
                $aryFieldDefine = array('id', 'name', 'position_id', 'rating', 'level');
                //need cross compare facebook friends list
                if($aryFacebookFriendList[$aryResident['facebook_id']]){
                    $objNeighbourhood->facebook_friend_id_list[] = $aryResident['facebook_id'];
                    $aryFieldDefine[] = 'facebook_id';
                }
                $objNeighbourhood->neighbourhood_resident_list[] = self::_packageObject(self::$__NeighbourhoodRegister, $aryResident, $aryFieldDefine);
            }
            $aryNeighbourhoodList[] = $objNeighbourhood;
        }

        return $aryNeighbourhoodList;
    }
    public static function verify_retrieveNeighbourhoodList($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = fasle;
        if(!is_array($objData->neighbourhood_list) || sizeof($objData->neighbourhood_list) > 9){//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $aryMsg['neighbourhood_list'] = 'Please supply neighbourhood id list and qty is 9';
        }else{
            $blnPass = true;
            foreach($objData->neighbourhood_list as $objNeighbourhood){
                $aryExportData['neighbourhood_list'][] = (int)$objNeighbourhood->id;
            }
        }
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    /**
     * Input:
     * objData.neighbourhood_id
     * 
     * Output:
     * objMapMatrix = (object)
     * objMapMatrix.neighbourhood_id = int
     * objMapMatrix.map_item_list = objMapItem[]
     * 
     * objMapItem.id = int
     * objMapItem.name = string
     * objMapItem.neighbourhood_id = int
     * objMapItem.neighbourhood_name = string
     * objMapItem.x = int
     * objMapItem.y = int
     * objMapItem.type = string //public|private|free
     * objMapItem.resident_id = int
     * objMapItem.credit = int
     * objMapItem.resident_id = int
     * objMapItem.reserve_to_current_user = bool //true|false
     * objMapItem.building_element_list = objBuildingElement[]
     * objMapItem.timestamp = datetime <-- for reserve time count down 
     * 
     * objBuildingElement.name = string
     * objBuildingElement.file_name = string// for fence fence_1.png?part[0]=file_name.png,x,y,mirror,z-index&part[1]=fence_door.png,x,y,mirror,z-index
     *                                                   house_1.png?ani[0]=file_name.fla,x,y,mirror,z-index&ani[1]=file_name.fla,x,y,mirror,z-index
     * objBuildingElement.mirror = 0/1
     * objBuildingElement.type = string (road or pet or house or fence)
     * objBuildingElement.z_index = int
     * objBuildingElement.element_task_list = objElementTask[]
     * 
     * objElementTask.name = string
     * objElementTask.enabled = bool //true|false
     * objElementTask.credit = int
     * objElementTask.rating = int
     * 
     * Example: 3.php
     **/
    public static $__retrieveMapMatrix = 'MapMatrix';
    public static function retrieveMapMatrix($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendList = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        
        $intNeighbourhoodId = (int)$aryData['neighbourhood_id'];
        //need pull out facebook info from session
        $objNeighbourhood = new db_neighbourhood();
        $objNeighbourhood->setId($intNeighbourhoodId);
        
        $aryNeighbourhood = db_neighbourhood::retrieveOneNeighbourhood($intNeighbourhoodId);
        $aryMapMatrixIdList = array();
        if(sizeof($aryNeighbourhood)){
            $aryMapMatrixIdList = db_mapmatrix::retrieveMapMatrixForOneNeighbourhood($intNeighbourhoodId);
        }
        
        $objNeighbourhood = self::_packageObject(self::$__retrieveNeighbourhood, $aryNeighbourhood, array('id'));
        $objNeighbourhood->server_time = date('Y-m-d H:i:s');
        $objNeighbourhood->map_item_list = array();
        //need cross compare facebook friends list
        foreach($aryMapMatrixIdList as $aryMapMatrixId){
            $objMapMatrix = self::retrieveMapItem(array('map_item_id' =>$aryMapMatrixId['id']));
            $objNeighbourhood->map_item_list[] = $objMapMatrix;
        }
        
        return $objNeighbourhood;
    }
    public static function verify_retrieveMapMatrix($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = false;
        if((int)$objData->neighbourhood_id > 1){
            $blnPass = true;
            $aryExportData['neighbourhood_id'] = (int)$objData->neighbourhood_id;
        }else{
            $aryMsg['neighbourhood_id'] = db_language::get('neighbourhood_id_error');
        }
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    
    /**
     * Input:
     * objData.map_item_id //mapmatrix id
     * 
     * Output:
     * objMapItem = (object)
     * objMapItem.id = int
     * objMapItem.name = string
     * objMapItem.neighbourhood_id = int
     * objMapItem.neighbourhood_name = string
     * objMapItem.x = int
     * objMapItem.y = int
     * objMapItem.type = string //public|private|free
     * objMapItem.resident_id = int
     * objMapItem.credit = int
     * objMapItem.resident_id = int
     * objMapItem.reserve_to_current_user = bool //true|false
     * objMapItem.building_element_list = objBuildingElement[]
     * objMapItem.timestamp = datetime <-- for reserve time count down 
     * 
     * objBuildingElement.name = string
     * objBuildingElement.file_name = string
     * objBuildingElement.element_task_list = objElementTask[]
     * 
     * objElementTask.name = string
     * objElementTask.enabled = bool //true|false
     * objElementTask.credit = int
     * objElementTask.rating = int
     * 
     * Example: 4.php
     **/
    public static $__retrieveMapItem = 'MapItem';
    public static $__retrieveBuildingElement = 'BuildingElement';
    public static $__retrieveElementTask = 'ElementTask';
    public static function retrieveMapItem($aryData)
    {
        //need pull out facebook info from session
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendList = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        
        $intMapMatrixId = (int)$aryData['map_item_id'];
        
        $aryMapMatrix = db_mapmatrix::retrieveOneMapMatrix($intMapMatrixId);
        
        $blnReserved = $aryMapMatrix['reserve_to_facebook_id'] && strtotime($aryMapMatrix['timestamp']) >= strtotime('now') ? true : false;
        
        $aryMapMatrix['facebook_id'] = $aryFacebookFriendList[$aryMapMatrix['facebook_id']]||$aryMapMatrix['resident_id']==$objCurrentResident->id ? $aryMapMatrix['facebook_id'] : '';
        $aryMapMatrix['resident_name'] = $aryMapMatrix['role_id'] < 10 ? '' : $aryMapMatrix['resident_name'];
        $aryMapMatrix['reserved_polt'] = $blnReserved;
        $aryMapMatrix['reserve_to_current_user'] = $blnReserved && $aryMapMatrix['reserve_to_facebook_id']==$objCurrentResident->facebook_id ? true : false;
        $aryMapMatrix['reserve_to_facebook_id'] = $blnReserved && $aryMapMatrix['resident_id']==$objCurrentResident->id ? $aryMapMatrix['reserve_to_facebook_id'] : '';
        
        $objMapMatrix = self::_packageObject(self::$__retrieveMapMatrix, $aryMapMatrix, array(      'id', 
                                                                                                    'name', 
                                                                                                    'x', 
                                                                                                    'y', 
                                                                                                    'type'=>'map_element_type_id', 
                                                                                                    'resident_id',
                                                                                                    'resident_name',
                                                                                                    'facebook_id',
                                                                                                    'reserved_polt',
                                                                                                    'reserve_to_facebook_id',
                                                                                                    'reserve_to_current_user',
                                                                                                    'timestamp',
                                                                                                    'credit',
                                                                                                    'sponsor',
                                                                                                    ));
        $objMapMatrix->building_element_list = array();
        foreach($aryMapMatrix['building_element_list'] as $aryResidentFieldBuilding){
            $objResidentFieldBuilding = self::_packageObject(self::$__retrieveBuildingElement, $aryResidentFieldBuilding, array(
                                    'id',
                                    'resident_element_id',
                                    'map_matrix_id',
                                    'file_name',
                                    'x',
                                    'y',
                                    'z_index',
                                    'mirror',
                                    'enabled',
                                    'enabled_by'));
            $objResidentFieldBuilding->element_task_list = array();
            if($aryResidentFieldBuilding['element_task_list'] && is_array($aryResidentFieldBuilding['element_task_list'])){
                foreach($aryResidentFieldBuilding['element_task_list'] as $aryTask){
                    $objTask = self::_packageObject(self::$__retrieveElementTask, $aryTask, array(
                            'resident_field_building_id',
                            'element_task_id',
                            'timestamp',
                            'enabled',
                            'name',
                            'credit',
                            'rating',
                            'stage',
                            'total_stage',
                            'message',
                        ));
                    $objResidentFieldBuilding->element_task_list[] = $objTask;
                }
            }
            $objMapMatrix->building_element_list[] = $objResidentFieldBuilding;    
        }
        return $objMapMatrix;
    }
    public static function verify_retrieveMapItem($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = false;
        if((int)$objData->map_item_id > 1){
            $blnPass = true;
            $aryExportData['map_item_id'] = (int)$objData->map_item_id;
        }else{
            $aryMsg['map_item_id'] = db_language::get('land_id_error');
        }
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    /**
     * Output:
     * objRegister = (object)
     * objRegister.name = string
     * objRegister.facebook_id = string
     * objRegister.credit = int
     * objRegister.neighbourhood_info_list = objNeighbourhoodInfo[]
     * 
     * objNeighbourhoodRating.neighbourhood_id = int
     * objNeighbourhoodRating.neighbourhood_name = string
     * objNeighbourhoodRating.neighbourhood_game_available = int
     * objNeighbourhoodRating.neighbourhood_game_total = int
     * objNeighbourhoodRating.rating = int <-- current user neighbourhood rating, not a neighbourhood total rating 
     * objNeighbourhoodRating.register_position = string
     * objNeighbourhoodRating.map_item_list = objMapItem[] <-- Only retrieve current user map item, include reserving map item and reserved map item 
     * 
     * objMapItem.id = int
     * objMapItem.name = string
     * 
     * Example: a.php
     **/
    
    public static $__retrievePersonalInfo = 'Resident';
    public static function retrievePersonalInfo()
    {
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendList = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        
        $aryNeighbourhoodInfoList = db_neighbourhood::retrieveNeighbourhoodInfoForResident($objCurrentResident->facebook_id, $objCurrentResident->retrieveId());
        $objResident = self::_packageObject(self::$__retrievePersonalInfo, get_object_vars($objCurrentResident), array(
                'name',
                'facebook_id',
                'credit',
                'notification'));
                
        
        $aryMessageListTmp = message_box::retrieveMessageList($objCurrentResident->id);
        $objResident->message_list = array();
        if(is_array($aryMessageListTmp)){
            foreach($aryMessageListTmp as $aryMessage){
                $objResident->message_list[] = self::_packageObject(self::$__retrieveAllMessage, $aryMessage, array(
                        'id',
                        'message',
                        'date',));
            }
        }
                
        $objResident->neighbourhood_info_list = array();
        foreach($aryNeighbourhoodInfoList as $aryNeighbourhoodInfo){
            $aryGameInfo = db_resident::retrieveNeighbourhoodGameInfoForResident($objCurrentResident->facebook_id, $aryNeighbourhoodInfo['neighbourhood_id']);
            $aryNeighbourhoodInfo['neighbourhood_game_available'] = $aryGameInfo['neighbourhood_game_available'];
            $aryNeighbourhoodInfo['neighbourhood_game_total'] = $aryGameInfo['neighbourhood_game_total'];
            
            $objNeighbourhood = self::_packageObject(self::$__retrieveNeighbourhood, $aryNeighbourhoodInfo, array(
                                                        'id'=>'neighbourhood_id', 
                                                        'name', 
                                                        'position_id', 
                                                        'rating', 
                                                        'level',
                                                        'neighbourhood_game_available',
                                                        'neighbourhood_game_total'
                                                        )
                                                     );
            $objNeighbourhood->map_item_list = array();
            foreach($aryNeighbourhoodInfo['map_item_list'] as $aryMapMatrix){
                $blnReserved = $aryMapMatrix['reserve_to_facebook_id'] && strtotime($aryMapMatrix['timestamp']) >= strtotime('now') ? true : false;
                
                $aryMapMatrix['reserved_polt'] = $blnReserved && $aryMapMatrix['reserve_to_facebook_id'] ? true : false;
                $aryMapMatrix['reserve_to_current_user'] = $blnReserved && $aryMapMatrix['reserve_to_facebook_id']==$objCurrentResident->facebook_id ? true : false;
                $aryMapMatrix['reserve_to_facebook_id'] = $blnReserved && $aryMapMatrix['resident_id']==$objCurrentResident->id ? $aryMapMatrix['reserve_to_facebook_id'] : '';
                $aryMapMatrix['resident_name'] = $aryMapMatrix['reserve_to_current_user'] ? $aryMapMatrix['resident_name'] : $objCurrentResident->name ;
            
                $objMapMatrix = self::_packageObject(self::$__retrieveMapMatrix, $aryMapMatrix, array(
                                            'id',
                                            'neighbourhood_id',
                                            'x',
                                            'y',
                                            'map_element_type_id',
                                            'resident_id',
                                            'resident_name',
                                            'reserved_polt',
                                            'reserve_to_current_user',
                                            'reserve_to_facebook_id',
                                            'timestamp',
                                            'name',
                                            'credit',
                                            'credit_for_sold',
                                        ));
                $objNeighbourhood->map_item_list[] = $objMapMatrix;
            }
            $objResident->neighbourhood_info_list[] = $objNeighbourhood;
        }
        return $objResident;
    }
    public static function verify_retrievePersonalInfo()
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    
    /**
     * Retrieve items for store
     * 
     * Output:
     * 
     * objBuildingElementCategoryList = objBuildingElementCategory[]
     * 
     * objBuildingElementCategory.id = int
     * objBuildingElementCategory.name = string
     * objBuildingElementCategory.desc = string
     * objBuildingElementCategory.building_element_list = objBuildingElement[]
     * 
     * objBuildingElement.id = int
     * objBuildingElement.name = string
     * objBuildingElement.desc = string
     * objBuildingElement.credit = int
     * objBuildingElement.file_name = string
     * 
     * Example: 7.php
     **/
    public static $__retrieveBuildingElementCategoryList = 'BuildingElementCategory';
    public static function retrieveBuildingElement($aryData)
    {
        //only retrieve suitable element type, some elements are only for public field
        
        //only retrieve suitable element type, some elements are only for public field
        $aryResidentElementTypeList = db_resident_field_building::retrieveResidentElementTypeList($aryData['int_map_element_type_id']);
        
        //_d($aryResidentElementTypeList,1,1);
        
        $aryExportTmp = array();
        foreach($aryResidentElementTypeList as $i => $aryResidentElementType){
            $aryResidentElementList = db_resident_field_building::retrieveResidentElementByMapElementTypeIdList($aryResidentElementType['id']);
            
            $aryResidentElementListTmp = array();
            $strCategoryName = '';
            foreach($aryResidentElementList as $n => $aryResidentElement){
                $aryTypeName = explode(',', $aryResidentElement['type_name']);
                if($aryResidentElement['credit'] > -1){
                    $strCategoryName = $aryTypeName[0];
                    $aryResidentElementListTmp[$n] = $aryResidentElement;
                    $aryResidentElementListTmp[$n]['type'] = $aryTypeName[1] ? $aryTypeName[1] : '';
                }
            }
            if(sizeof($aryResidentElementListTmp) < 1){
                continue;
            }
            
            $aryExportTmp[$strCategoryName]['name'] = $strCategoryName?$strCategoryName:$aryResidentElementType['id'];
            foreach($aryResidentElementListTmp as $aryRow){
                $aryExportTmp[$strCategoryName]['building_element_list'][] = $aryRow;
            }
            
        }
        $aryExport = array();
        foreach($aryExportTmp as $strCategoryName => $aryResidentElementType){
                $objBuildingElementCategory = self::_packageObject(self::$__retrieveBuildingElementCategoryList, $aryResidentElementType, array(
                    //'id',
                    'name',));
                $objBuildingElementCategory->building_element_list = array();
                
                foreach($aryResidentElementType['building_element_list'] as $aryResidentElement){
                    $objBuildingElementCategory->building_element_list[] = self::_packageObject(self::$__retrieveBuildingElement, $aryResidentElement, array(
                    'id', 'name', 'credit', 'type', 'file_name',));
                }
                $aryExport[] = $objBuildingElementCategory;
        }
        return $aryExport;
    }
    
    public static function verify_retrieveBuildingElement($objData = null)
    {
        $aryMsg = array();
        $aryExportData = array();
        if(!$objData || !$objData->int_map_element_type_id || ($objData->int_map_element_type_id != 1 && $objData->int_map_element_type_id != 2 && $objData->int_map_element_type_id != 3)){
            $aryExportData['int_map_element_type_id'] = 3;
        }else{
            $aryExportData['int_map_element_type_id'] = $objData->int_map_element_type_id;
        }
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    /**
     * Output:
     * objNeighbourhoodList = objNeighbourhood[]
     * 
     * objNeighbourhood.id = int
     * objNeighbourhood.name = string
     * objNeighbourhood.register_list = objRegister[]
     * 
     * objRegister = (object)
     * objRegister.name = string
     * objRegister.facebook_id = string
     * objRegister.map_item_qty = int
     * objRegister.register_position = string
     * 
     * Example: b.php
     **/
    public static $__retrieveVerifiedFriendsList = 'Friends';
    public static function retrieveVerifiedFriendsList()
    {
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendListTmp = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        $aryFacebookFriendList = array();
        if(is_array($aryFacebookFriendListTmp)){
            foreach($aryFacebookFriendListTmp as $aryFacebookFriend){
                $aryFacebookFriendList[] = self::_packageObject(self::$__retrieveVerifiedFriendsList, $aryFacebookFriend, array(
                        'id',
                        'name',));
            }
        }
        
        return $aryFacebookFriendList;
    }
    public static function verify_retrieveVerifiedFriendsList()
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    /**
     * Output:
     * objNeighbourhoodList = objNeighbourhood[]
     * 
     * objNeighbourhood.id = int
     * objNeighbourhood.name = string
     * objNeighbourhood.register_list = objRegister[]
     * 
     * objRegister = (object)
     * objRegister.name = string
     * objRegister.facebook_id = string
     * objRegister.map_item_qty = int
     * objRegister.register_position = string
     * 
     * Example: b.php
     **/
    public static $__retrieveLanguage = 'Friends';
    public static function retrieveLanguage($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendListTmp = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        $aryFacebookFriendList = array();
        if(is_array($aryFacebookFriendListTmp)){
            foreach($aryFacebookFriendListTmp as $aryFacebookFriend){
                $aryFacebookFriendList[] = self::_packageObject(self::$__retrieveVerifiedFriendsList, $aryFacebookFriend, array(
                        'id',
                        'name',));
            }
        }
        
        return $aryFacebookFriendList;
    }
    public static function verify_retrieveLanguage($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }

    /**
     * Retrieve all message
     * 
     * objMessageBoxList[] = objMessageBox[]
     * objMessageBox.id = int
     * objMessageBoX.message = string
     * objMessageBoX.date = string
     * 
     * Example: c.php
     **/
    public static $__retrieveAllMessage = 'MessageBox';
    public static function retrieveAllMessage()
    {
        $objCurrentResident = db_resident::get_handle();
        
        $aryMessageListTmp = message_box::retrieveMessageList($objCurrentResident->id, true);
        $aryMessageList = array();
        if(is_array($aryMessageListTmp)){
            foreach($aryMessageListTmp as $aryMessage){
                $aryMessageList[] = self::_packageObject(self::$__retrieveAllMessage, $aryMessage, array(
                        'id',
                        'message',
                        'date',));
            }
        }
        return $aryMessageList;
    }
    public static function verify_retrieveAllMessage()
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    /**
     * Output:
     * objNeighbourhoodList = objNeighbourhood[]
     * 
     * objNeighbourhood.id = int
     * objNeighbourhood.name = string
     * objNeighbourhood.register_list = objRegister[]
     * 
     * objRegister = (object)
     * objRegister.name = string
     * objRegister.facebook_id = string
     * objRegister.map_item_qty = int
     * objRegister.register_position = string
     * 
     * Example: d.php
     **/
    public static $__retrieveFriendInfo = 'ResidentInfo';
    public static function retrieveFriendInfo($aryData)
    {
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendList = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        
        $objResident = new db_resident();
        $objResident->facebook_id = $aryData['facebook_id'];
        $objResident->retrieve();
        
        $aryNeighbourhoodInfoList = db_neighbourhood::retrieveNeighbourhoodInfoForResident($objResident->facebook_id, $objResident->id);
        
        $objResident = self::_packageObject(self::$__retrieveFriendInfo, get_object_vars($objResident), array(
                'id',
                'name',
                'facebook_id',));
        $objResident->neighbourhood_info_list = array();
        foreach($aryNeighbourhoodInfoList as $aryNeighbourhoodInfo){
            $objNeighbourhood = self::_packageObject(self::$__retrieveNeighbourhood, $aryNeighbourhoodInfo, array('id'=>'neighbourhood_id', 'name', 'position_id', 'level'));
            $objNeighbourhood->map_item_list = array();
            foreach($aryNeighbourhoodInfo['map_item_list'] as $aryMapMatrix){
                if(!$aryMapMatrix['reserve_to_facebook_id']){
                    $objMapMatrix = self::_packageObject(self::$__retrieveMapMatrix, $aryMapMatrix, array(
                                            'id',
                                            'neighbourhood_id',
                                            'x',
                                            'y',
                                            'map_element_type_id',
                                            'timestamp',
                                            'name',
                                            'credit',
                                        ));
                    $objNeighbourhood->map_item_list[] = $objMapMatrix;
                }
            }
            $objResident->neighbourhood_info_list[] = $objNeighbourhood;
        }
        return $objResident;
    }
    public static function verify_retrieveFriendInfo($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = false;
        
        $objCurrentResident = db_resident::get_handle();
        $aryFacebookFriendList = local_data::get($objCurrentResident->facebook_id, 'facebook_friend_list');
        
        if(!$aryFacebookFriendList[$objData->facebook_id]){
            $aryMsg['FriendInfo'] = db_language::get('facebook_friend_info_require_error');
        }else{
            $aryExportData['facebook_id'] = $objData->facebook_id;
            $blnPass = true;
        }
        
        return array($blnPass, $aryMsg, $aryExportData);
    }

    /**
     * Output:
     * objNeighbourhoodList = objNeighbourhood[]
     * 
     * objNeighbourhood.id = int
     * objNeighbourhood.name = string
     * objNeighbourhood.register_list = objRegister[]
     * 
     * objRegister = (object)
     * objRegister.name = string
     * objRegister.facebook_id = string
     * objRegister.map_item_qty = int
     * objRegister.register_position = string
     * 
     * Example: i.php
     **/
    public static $__retrieveFileList = 'File';
    public static function retrieveFileList()
    {
    }
    public static function verify_retrieveFileList()
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////    
//////////                         //////////////////////////////////////////////////////////////////////    
//////////  game related           //////////////////////////////////////////////////////////////////////    
/////////                          //////////////////////////////////////////////////////////////////////    
/////////////////////////////////////////////////////////////////////////////////////////////////////////    
        
    
        
     /**
     * Input: none
     * 
     * @return : 
     *             [status:"successfu",message:"",
     *                 result:list of  object of com.miniGame.list 
     *                     [
     *                         {game_id:5, name:"plant tree", description:"tree ...", point:5, min_time:20, max_time:50, per_day:1}, 
     *                         .... ,
     *                         .... ,
     *                     ]
     *             ]
     *                 *time is in second!
     * Example: e.php
     **/
    public static $_listOfGames = 'listOfGames';
    public static function listOfGames($objData=null)
    {
    //    $player = games::get_player();
        $o = new stdClass();
        $o->cmd = get_game_list;
        
        $objExport = games::dispatch($o);
        
        return $objExport;
    }
    public static function verify_listOfGames($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = true;
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
    

//test interface//////////////////////////////////////    
    public static function test($arr=null){
        return games::get_all_games(array());

    }
    
    
    
    /**
     * Input:
     * $objData->scoreboard_name = mayor_score, overall
     * $objData->order_name = rating (when scoreboard_name = mayor_score)
     * $objData->order_name = total_supported_property_qty, mayor_ambassador_points, mayorship (when scoreboard_name = overall)
     * 
     * Output:
     * objScoreboard = (object)
     * objScoreboard.data = objScoreData[]
     * objScoreboard.language = string[,]
     * 
     * (when scoreboard_name = mayor_score):
     * objScoreData.resident_id
     * objScoreData.neighbourhood_id
     * objScoreData.mayor_name
     * objScoreData.neighbourhood_name
     * objScoreData.mayor_supported_property_qty
     * objScoreData.mayor_level
     * objScoreData.mayor_ambassador_points
     * 
     * (when scoreboard_name = overall):
     * objScoreData.resident_id
     * objScoreData.resident_name
     * objScoreData.total_supported_property_qty
     * objScoreData.mayor_ambassador_points
     * objScoreData.mayorship
     * 
     * Example: m.php
     **/
    public static $__retrieveScoreboard = 'Scoreboard';
    public static $__retrieveScoreData = 'ScoreData';
    public static function retrieveScoreboard($aryData)
    {
        $aryScoreboardTmp = db_scoreboard::getScoreBoard($aryData['scoreboard_name'], $aryData['order_name']);
        
        $objScoreboardReturn = self::_packageObject(self::$__retrieveScoreboard, array(), array());
        $objScoreboardReturn->data = array();
        $objScoreboardReturn->language = array();
        
        if(is_array($aryScoreboardTmp) && sizeof($aryScoreboardTmp) > 0){
            $aryLanguageKey = array_keys($aryScoreboardTmp[0]);
            
            $objScoreboardReturn->language = db_scoreboard::mappingLanguage($aryLanguageKey);
            
            foreach($aryScoreboardTmp as $aryScoreboardRow){
                $objScoreData = self::_packageObject(self::$__retrieveScoreData, $aryScoreboardRow, $aryLanguageKey);
                $objScoreboardReturn->data[] = $objScoreData;
            }
        }
        
        return $objScoreboardReturn;
    }
    public static function verify_retrieveScoreboard($objData)
    {
        $aryMsg = array();
        $aryExportData = array();
        $blnPass = false;
        
        $aryExportData['scoreboard_name'] = $objData->scoreboard_name;
        $aryExportData['order_name'] = $objData->order_name;
        if(!isset(db_scoreboard::$aryModeList[$aryExportData['scoreboard_name']])){
            $aryMsg['scoreboard_name'] = db_language::get('scoreboard is not exist');;
        }elseif(!isset(db_scoreboard::$aryModeList[$aryExportData['scoreboard_name']][$aryExportData['order_name']])){
            $aryMsg['order_name'] = db_language::get('order field is not exist');;
        }else{
            $blnPass = true;
        }
        
        return array($blnPass, $aryMsg, $aryExportData);
    }
    
}
