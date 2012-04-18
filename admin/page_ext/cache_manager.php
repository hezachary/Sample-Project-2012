<?php
function fnCacheManagerPageContent($aryCondition, $intPageNum, $aryOrder){
    $aryNeighbourhoodIdList = db_neighbourhood::retrieveAllNeighbourhoodId();
    $aryNeighbourhoodList = array();
    foreach($aryNeighbourhoodIdList as $aryNeighbourhood){
         $aryNeighbourhoodTmp = db_neighbourhood::retrieveOneNeighbourhood($aryNeighbourhood['id']);
         $aryNeighbourhoodTmp['template_name'] = db_neighbourhood::retrieveFieldByCondition(db_neighbourhood::strTable, $aryNeighbourhoodTmp['template_id'], 'id', 'name');
         $aryNeighbourhoodList[] = $aryNeighbourhoodTmp;
    }
	$page_content = array();
	$page_content['neighbourhood_list'] = $aryNeighbourhoodList;
	return $page_content;
}