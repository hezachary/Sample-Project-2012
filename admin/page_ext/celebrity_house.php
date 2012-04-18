<?php
function fnCelebrityHousePageContent(){
    //1. Preset Element Type Try to retrieve
    $intResidentElementTypeId = 251;
    
    //2. Retrieve All Celebrity House Setting
    $aryCelebrityHouseElementListTmp = db_resident_field_building::retrieveResidentElementByMapElementTypeIdList($intResidentElementTypeId);
    $aryCelebrityHouseElementList = array();
    foreach($aryCelebrityHouseElementListTmp as $aryCelebrityHouseElementTmp){
        $aryCelebrityHouseElementList[$aryCelebrityHouseElementTmp['id']] = $aryCelebrityHouseElementTmp['name'];
    }
    
    //3. Retrieve all neighbourhood, and populate neighbourhood info
    $aryNeighbourhoodIdList = db_neighbourhood::retrieveAllNeighbourhoodId();
    $aryNeighbourhoodCelebrityList = array();
    foreach($aryNeighbourhoodIdList as $aryNeighbourhoodId){
        $aryNeighbourhoodTmp = db_neighbourhood::retrieveOneNeighbourhood($aryNeighbourhoodId['id']);
        $aryNeighbourhoodCelebrityList[$aryNeighbourhoodId['id']]['neighbourhood'] = $aryNeighbourhoodTmp;
    }
    
    //4. Retrieve all Celebrity House
    $aryCelebrityHouseCondition = array();
    $aryCelebrityHouseCondition['z_index'] = $intResidentElementTypeId;
    $aryCelebrityHouseSelect = array(
                                    'id',
                                    'resident_element_id',
                                    'map_matrix_id',
                                    'file_name',
                                    'x',
                                    'y',
                                    'z_index',
                                    'mirror',
                                    'timestamp',
                                    );
    $aryCurrentCelebrityHouseList = db_resident_field_building::retrieveList(db_resident_field_building::strTable, $aryCelebrityHouseCondition, 0, -999999, $aryCelebrityHouseSelect);
    if(is_array($aryCurrentCelebrityHouseList) && sizeof($aryCurrentCelebrityHouseList)){
        
        //5. Retrieve Celebrity related map plot
        $aryMapMatrixIdList = array();
        $aryMapMatrixCelebrityList = array();
        foreach($aryCurrentCelebrityHouseList as $aryCurrentCelebrityHouse){
            $aryMapMatrixIdList[] = $aryCurrentCelebrityHouse['map_matrix_id'];
            $aryMapMatrixCelebrityList[$aryCurrentCelebrityHouse['map_matrix_id']] = $aryCurrentCelebrityHouse;
        }
        $aryMapMatrixCondition = array();
        $aryMapMatrixCondition['id'] = $aryMapMatrixIdList;
        $aryMapMatrixSelect = array(
                                                'id',
                                                'neighbourhood_id',
                                                'x',
                                                'y',
                                                'map_element_type_id',
                                            );
        $aryMapMatrixListTmp = db_mapmatrix::retrieveList(db_mapmatrix::strTable, $aryMapMatrixCondition, 1, -99999, $aryMapMatrixSelect, false, array('neighbourhood_id ASC'));
        
        //6. Insert back the celebrity house info back to neighbouthood
        foreach($aryMapMatrixListTmp as $aryMapMatrixTmp){
            $aryNeighbourhoodCelebrityList[$aryMapMatrixTmp['neighbourhood_id']]['mapmatrix'] = $aryMapMatrixTmp;
            $aryNeighbourhoodCelebrityList[$aryMapMatrixTmp['neighbourhood_id']]['resident_field_building'] = $aryMapMatrixCelebrityList[$aryMapMatrixTmp['id']];
            $aryNeighbourhoodCelebrityList[$aryMapMatrixTmp['neighbourhood_id']]['resident_field_building']['resident_element_name'] = $aryCelebrityHouseElementList[$aryMapMatrixCelebrityList[$aryMapMatrixTmp['id']]['resident_element_id']]['name'];
        }
    }
    
	$page_content = array();
	$page_content['neighbourhood_list'] = $aryNeighbourhoodCelebrityList;
    $page_content['celebrity_house_element_list'] = $aryCelebrityHouseElementList;
	return $page_content;
}