<?php

function fnMapEditorPageContent(){
    $aryNeighbourhoodCondtion = array();
    $aryNeighbourhoodCondtion['template'] = 1; 
    $aryNeighbourhoodList = db_neighbourhood::retrieveList(db_neighbourhood::strTable, $aryNeighbourhoodCondtion, 0, -9999, array('id', 'name', 'published'));
    
    $aryResidentElementTypeListTmp = db_resident_field_building::retrieveList(db_resident_field_building::strResidentElementTypeTable, array(), 0, -9999, array('id', 'name', 'available', 'map_element_type_id'), false, array('map_element_type_id'));
    $aryResidentElementListTmp = db_resident_field_building::retrieveList(db_resident_field_building::strResidentElementTable, array(), 0, -9999, array('id', 'name', 'credit', 'credit_for_sold', 'type_name', 'resident_element_type_id', 'file_name'));
    
    $aryResidentElementList = array();
    foreach($aryResidentElementListTmp as $aryResidentElement){
        $aryResidentElementList[$aryResidentElement['resident_element_type_id']][$aryResidentElement['id']] = $aryResidentElement;
    }
    $aryResidentElementTypeList = array();
    foreach($aryResidentElementTypeListTmp as $aryResidentElementType){
        $aryResidentElementTypeList[$aryResidentElementType['map_element_type_id']][$aryResidentElementType['id']] = $aryResidentElementType;
        $aryResidentElementTypeList[$aryResidentElementType['map_element_type_id']][$aryResidentElementType['id']]['resident_element_list'] = $aryResidentElementList[$aryResidentElementType['id']]; 
    }
    
    if($_POST['map_data']){
        $aryMapDataTmp = explode(';', $_POST['map_data']);
        $aryMapData = array();
        $timestamp = date('Y-m-d H:i:s');
        foreach($aryMapDataTmp as $strImgData){
            list($intMapElementTypeId, $x, $y, $intResidentElementTypeId, $intResidentElementId, $intMirror) = explode(',', $strImgData);
            $aryResidentElementFileSetting = db_resident_field_building::parseFilename($aryResidentElementList[$intResidentElementTypeId][$intResidentElementId]['file_name']);
            $strFileName = $aryResidentElementList[$intResidentElementTypeId][$intResidentElementId]['file_name'];
            if(is_array($aryResidentElementFileSetting['query']['part'])){
                foreach($aryResidentElementFileSetting['query']['part'] as $intPartIndex => $aryPartSettings){
                    $aryResidentElementFileSetting['query']['part'][$intPartIndex][3] = $intMirror;
                }
                $strFileName = db_resident_field_building::httpBuildFilename($aryResidentElementFileSetting);
            }
            $aryMapData[$x.'_'.$y]['x'] = $x;
            $aryMapData[$x.'_'.$y]['y'] = $y;
            $aryMapData[$x.'_'.$y]['map_element_type_id'] = $intMapElementTypeId;
            $aryMapData[$x.'_'.$y]['resident_id'] = 1;
            $aryMapData[$x.'_'.$y]['reserve_to_facebook_id'] = '';
            $aryMapData[$x.'_'.$y]['timestamp'] = $timestamp;
            $aryMapData[$x.'_'.$y]['name'] = '';
            $aryMapData[$x.'_'.$y]['credit'] = $intMapElementTypeId == 2 ? db_mapmatrix::strCreditPerPlot : 0;
            $aryMapData[$x.'_'.$y]['credit_for_sold'] = $intMapElementTypeId == 2 ? db_mapmatrix::strCreditSellPerPlot : 0;
            $aryMapData[$x.'_'.$y]['resident_field_building_list'][] = array(
                                                'resident_element_id'   => $intResidentElementId,
                                                'x'                     => $x,
                                                'y'                     => $y,
                                                'z_index'               => $intResidentElementTypeId,
                                                'mirror'                => $intMirror,
                                                'file_name'             => $strFileName,
                                                'timestamp'             => $timestamp,
                                                'enabled'               => $intMapElementTypeId == 1 && $intResidentElementTypeId > 200 ? '0' : 1,
                                                'building_task_status_list' => false,
                                                );
        }
        if(sizeof($aryMapData) > 0){
            if($_POST['status_mode'] == 2 && $_POST['neighbourhood_id']){
                //remove data
                $intNeighbourhoodIdTmp = (int)$_REQUEST['neighbourhood_id'];
                $aryCondition = array();
                $aryCondition['neighbourhood_id'] = $intNeighbourhoodIdTmp;
                $arySelect = array('id');
                $aryMapMatrixList = db_mapmatrix::retrieveList(db_mapmatrix::strTable, $aryCondition, 0, -99999, $arySelect);
                if(sizeof($aryMapMatrixList) != sizeof($aryMapData)){
                    printf('Map plots qty is not match, existing qty is %d, submited qty is %d, operation failed!', sizeof($aryMapMatrixList), sizeof($aryMapData));
                    die();
                }
                foreach($aryMapMatrixList as $key => $aryMapMatrix){
                    db_resident_field_building::removeData(db_resident_field_building::strTable, 'map_Matrix_id', $aryMapMatrix['id']);
                }
                db_mapmatrix::removeData(db_mapmatrix::strTable, 'neighbourhood_id', $intNeighbourhoodIdTmp);
                db_neighbourhood::removeData(db_neighbourhood::strTable, 'id', $intNeighbourhoodIdTmp);
            }
            
            $objNeighbouthood = new db_neighbourhood();
            $objNeighbouthood->name = $_POST['neighbourhood_name'];
            $objNeighbouthood->template = 1;
            $objNeighbouthood->published = isset($_POST['published']) ? 1 : 0;
            $objNeighbouthood->populateNeighbourhood($aryMapData);
        }
        header('Location: admin_index.php?section=map_editor');
        exit();
        //_d($aryMapData,1,1);
    }
    
    $aryMapMatrixList = array();
    if(!empty($_REQUEST['neighbourhood_id'])){
        $aryCondition = array();
        $aryCondition['neighbourhood_id'] = $_REQUEST['neighbourhood_id'];
        $arySelect = array('id','x','y','map_element_type_id');
        $aryMapMatrixList = db_mapmatrix::retrieveList(db_mapmatrix::strTable, $aryCondition, 0, -99999, $arySelect);
        
        foreach($aryMapMatrixList as $key => $aryMapMatrix){
            $aryCondition = array();
            $aryCondition['map_Matrix_id'] = $aryMapMatrix['id'];
            $arySelect = array(
                                'resident_element_id',
                                'z_index',
                                'mirror',
                                'file_name',
                                );
            $aryMapMatrixList[$key]['resident_field_building_list'] = db_resident_field_building::retrieveList(db_resident_field_building::strTable, $aryCondition, 0, -99999, $arySelect);
        }
    }
    
	$page_content = array();
	$page_content['ary_map_matrix_list'] = Zend_Json::encode($aryMapMatrixList);
	$page_content['neighbourhood_id'] = (int)$_REQUEST['neighbourhood_id'];
	$page_content['ary_neighbourhood_list'] = $aryNeighbourhoodList;
	$page_content['ary_element_list'] = Zend_Json::encode($aryResidentElementTypeList);
    
    return $page_content;
}