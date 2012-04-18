<?php
function fnOrderListPageContent($aryCondition, $intPageNum, $aryOrder){
    
    list($aryCondition, $strSearchField, $strData) = fnRetrieveSearchField($aryCondition, $_REQUEST, db_resident::strTable);
    $arySelect = array(                     'id',
                                            'FirstName',
                                            'LastName',
                                            'Email',
                                            'CardHoldersName',
                                            'CardMaskedNumber',
                                            'PlanName',
                                            'Cost',
                                            'resident_id',
                                            'facebook_id',
                                            'eway_return_msg',
                                            'timestamp',
                                            'trans_no',
                                            'order_status',);
    
    
	$intPageStep = 100;
	list($aryResult, $intPageNum, $intTotalPage) = db_order::retrieveList(db_order::strTable, $aryCondition, $intPageNum, $intPageStep, $arySelect, false, $aryOrder);
	
	$page_content = array();
	$page_content['order_list'] = $aryResult;
	$page_content['page'] = $intPageNum;
	
	$page_content['page_total'] = $intTotalPage;
	$page_content['page_list'] = $intTotalPage?range(1, $intTotalPage):array();
	
	$strOrder = current($aryOrder);
	$arySingleOrder = explode(' ', $strOrder);
	$page_content['order'] = $arySingleOrder[0];
	$page_content['asc'] = strtolower($arySingleOrder[1])=='desc'?'false':'true';
	
	$aryFieldName = array('facebook_id'=>'Facebook Id', 'trans_no'=>'Transaction No.', 'CardHoldersName'=>'Card Holders Name', 'timestamp'=>'Timestamp');
	$page_content['aryFieldName'] = $aryFieldName;
	$page_content['search_field'] = $strSearchField;
	$page_content['data'] = $strData;
    
    $aryCondition = array();
	$page_content['int_csv_total'] = db_order::retrieveList(db_order::strTable, $aryCondition, 0, 1, array(), true);
	
    $aryCondition['created_0'] = array();
    $aryCondition['created_0'][] = array('timestamp', '>=', date('Y-m-d 00:00:00'));
	$page_content['int_csv_today'] = db_order::retrieveList(db_order::strTable, $aryCondition, 1, 1, array(), true);
    
    $aryCondition['created_0'] = array();
    $aryCondition['created_1'] = array();
    $aryCondition['created_0'][] = array('timestamp', '>=', date('Y-m-d 00:00:00', strtotime('yesterday')));
    $aryCondition['created_1'][] = array('timestamp', '<=', date('Y-m-d 23:59:59', strtotime('yesterday')));
	$page_content['int_csv_yesterday'] = db_order::retrieveList(db_order::strTable, $aryCondition, 1, 1, array(), true);
    return $page_content;
}