<?php
function fnPlayerListPageContent($aryCondition, $intPageNum, $aryOrder){
    
    list($aryCondition, $strSearchField, $strData) = fnRetrieveSearchField($aryCondition, $_REQUEST, db_resident::strTable);
    $aryCondition['role_id'][] = array('role_id','>','10');
    $arySelect = array(                     'id',
                                            'role_id',
                                            'name',
                                            'credit',
                                            'facebook_id',
                                            'email',
                                            'notification',
                                            'currentlogin',
                                            'currentip',
                                            'current_login_session_id',
                                            'lastlogin',
                                            'lastip',
                                            'last_login_session_id',
                                            'created');
    
	$intPageStep = 100;
	list($aryResult, $intPageNum, $intTotalPage) = db_resident::retrieveList(db_resident::strTable, $aryCondition, $intPageNum, $intPageStep, $arySelect, false, $aryOrder);
	
	$page_content = array();
	$page_content['resident_list'] = $aryResult;
	$page_content['page'] = $intPageNum;
	
	$page_content['page_total'] = $intTotalPage;
	$page_content['page_list'] = $intTotalPage?range(1, $intTotalPage):array();
	
	$strOrder = current($aryOrder);
	$arySingleOrder = explode(' ', $strOrder);
	$page_content['order'] = $arySingleOrder[0];
	$page_content['asc'] = strtolower($arySingleOrder[1])=='desc'?'false':'true';
	
	$aryFieldName = array('first_name'=>'Resident Name', 'email'=>'Resident Email', 'facebook_id'=>'Facebook Id', 'created'=>'Account Created');
	$page_content['aryFieldName'] = $aryFieldName;
	$page_content['search_field'] = $strSearchField;
	$page_content['data'] = $strData;
	
    $aryCondition = array();
    $aryCondition['role_id'][] = array('role_id','>','10');
	$page_content['int_csv_total'] = db_resident::retrieveList(db_resident::strTable, $aryCondition, 1, 1, $arySelect, true);
    
    $aryCondition['created_0'] = array();
    $aryCondition['created_0'][] = array('created', '>=', date('Y-m-d 00:00:00'));
	$page_content['int_csv_today'] = db_resident::retrieveList(db_resident::strTable, $aryCondition, 1, 1, array(), true);
    
    $aryCondition['created_0'] = array();
    $aryCondition['created_1'] = array();
    $aryCondition['created_0'][] = array('created', '>=', date('Y-m-d 00:00:00', strtotime('yesterday')));
    $aryCondition['created_1'][] = array('created', '<=', date('Y-m-d 23:59:59', strtotime('yesterday')));
	$page_content['int_csv_yesterday'] = db_resident::retrieveList(db_resident::strTable, $aryCondition, 1, 1, array(), true);
    
	return $page_content;
}

function fnPlayerInfoPageContent($intResidentId, $aryCondition, $intPageNum, $aryOrder){
    $objResident = new db_resident();
    $objResident->id = $intResidentId;
    $aryResident = $objResident->retrieve();
    
    $aryNeighbourhoodInfoList = db_neighbourhood::retrieveNeighbourhoodInfoForResident(null, $intResidentId, true);
    
    $aryCupponHistorySelect = array(        db_cuppon_resident_history::strTable.'.cuppon_id',
                                            db_cuppon_resident_history::strCupponTable.'.cuppon_code',
                                            db_cuppon_resident_history::strTable.'.credit',
                                            db_cuppon_resident_history::strCupponTable.'.partner',
                                            db_cuppon_resident_history::strTable.'.timestamp',
                                            );
    $aryCupponHistoryOrder = array('timestamp DESC');
    $aryCupponHistoryList = db_cuppon_resident_history::retrieveCupponResidentHistoryList($intResidentId, $aryCupponHistorySelect, $aryCupponHistoryOrder);
    
    list($aryOrderCondtion, $strSearchField, $strData) = fnRetrieveSearchField($aryCondition, $_REQUEST, db_order::strTable);
    $aryOrderCondtion['resident_id'] = $intResidentId;
    $aryOrderSelect = array(                'id',
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
    
	$intPageStep = 50;
	list($aryOrderResult, $intPageNum, $intTotalPage) = db_order::retrieveList(db_order::strTable, $aryOrderCondtion, $intPageNum, $intPageStep, $aryOrderSelect, false, $aryOrder);
	
	$page_content = array();
	$page_content['ary_resident'] = $aryResident;
	$page_content['ary_neighbourhood_info_list'] = $aryNeighbourhoodInfoList;
	$page_content['ary_cuppon_history_list'] = $aryCupponHistoryList;
	$page_content['order_list'] = $aryOrderResult;
	$page_content['page'] = $intPageNum;
	
	$page_content['page_total'] = $intTotalPage;
	$page_content['page_list'] = $intTotalPage?range(1, $intTotalPage):array();
	
	$strOrder = current($aryOrder);
	$arySingleOrder = explode(' ', $strOrder);
	$page_content['order'] = $arySingleOrder[0];
	$page_content['asc'] = strtolower($arySingleOrder[1])=='desc'?'false':'true';
	
	$aryFieldName = array('trans_no'=>'Transaction No.', 'CardHoldersName'=>'Card Holders Name', 'timestamp'=>'Timestamp');
	$page_content['aryFieldName'] = $aryFieldName;
	$page_content['search_field'] = $strSearchField;
	$page_content['data'] = $strData;
    
	$page_content['int_csv_total'] = db_order::retrieveList(db_order::strTable, array('resident_id' => $intResidentId), 0, 1, array(), true);
    return $page_content;
}