<?php
require_once ('admin_config.php');
global $PAGE;
$objCurrentUser = user::get_handle();
if(!$objCurrentUser->ACL->admin['view']){
	header('Location: admin_login.php?error=1&link='.urlencode($_SERVER['QUERY_STRING']));
	exit;
}

if (isset ( $_POST ['ext_mode'] ) && isset ( $PAGE [$_POST ['ext_mode']] )) {
	include ($PAGE [$_POST ['ext_mode']]->dir_path . (isset ( $PAGE [$_POST ['ext_mode']]->ajax_php ) ? $PAGE [$_POST ['ext_mode']]->ajax_php : $PAGE [$_POST ['ext_mode']]->default_php));
	exit ();
}
$CFG->section = 'register';
$register_id = ( int ) $_POST ['id'];
$aryData = fnProcessData ( $_POST ['data'] );

switch ($_POST ['mode']) {
    case 'cache_manager':
        $objExport = new stdClass();
        $blnSuccess = true;
        $strText = 'Rebuild Cache Successful';
        switch($_POST['command']){
            case 'retrieveSponsorList':
                db_sponsor::retrieveSponsorList(true);
                break; 
            case 'retrieveLanguageList':
                db_language::retrieveLanguageList(null, true);
                break;
            case 'retrieveNeighbourhoodLevelSettings':
                db_neighbourhood::retrieveLevelSettings(true);
                break;
            case 'retrieveResidentLevelSettings':
                db_resident::retrieveLevelSettings(true);
                break;
            case 'retrieveResidentElementTypeList':
                $aryResidentElementTypeList = db_resident_field_building::retrieveResidentElementTypeList(null, true);
                foreach($aryResidentElementTypeList as $aryResidentElementType){
                    db_resident_field_building::retrieveResidentElementByMapElementTypeIdList($aryResidentElementType['id'], true);
                }
                break;
            case 'retrieveMapImageList':
                db_resident_field_building::retrieveMapImageList(true);
                break;
            case 'retrieveTaskSettings':
                db_task::retrieveTaskSettings(true);
                break;
            default:
                $intNeighbourhoodId = (int)$_POST['command'];
                if($intNeighbourhoodId > 0){
                    db_neighbourhood::retrieveOneNeighbourhood($intNeighbourhoodId, true);
                    $aryMapMatrixIdList = db_mapmatrix::retrieveMapMatrixForOneNeighbourhood($intNeighbourhoodId, true);
                    foreach($aryMapMatrixIdList as $aryMapMatrixId){
                        db_mapmatrix::retrieveOneMapMatrix($aryMapMatrixId['id'], true);
                    }
                }else{
                    $blnSuccess = false;
                    $strText = 'Unknow Command';
                }
                break;
        }
		$objExport->valid = $blnSuccess;
		$objExport->text = $strText;
		echo Zend_Json::encode ( $objExport );
        break;
    case 'celebrity_house':
        $objExport = new stdClass();
        $blnSuccess = true;
        $strText = 'Done';
        switch($_POST['command']){
            case 'send_message_to_residents':
                //send message if neighbourhood is full
                $aryNeighbourhood = db_neighbourhood::retrieveOneNeighbourhood((int)$_POST['neighbourhood_id'], true);
                
                $aryResidentElementCondition = array();
                $aryResidentElementCondition['id'] = $_REQUEST['resident_element_id'];
                $aryResidentElementSelect = array('id', 'name', 'credit', 'reward_rating', 'resident_element_type_id', 'file_name');
                $aryResidentElement = db_resident_field_building::retrieveList(db_resident_field_building::strResidentElementTable, $aryResidentElementCondition, 0, 0, $aryResidentElementSelect);
            
                $aryData = array(
                        'neighbourhood_id'      => $aryNeighbourhood['id'],
                        'neighbourhood_name'    => $aryNeighbourhood['name'],
                        'map_matrix_id'         => (int)$_POST['map_matrix_id'],
                        'resident_element_name' => $aryResidentElement['name'],
                        );
                $strMessage = db_language::get('celebrity_house_alerts', $aryData, false, true);
                
                $strFlag = $aryNeighbourhood['id'].'_'.$aryResidentElement['id'];
                $aryMessageCondition = array();
                $aryMessageCondition['flag'] = $strFlag;
                $aryMessageSelect = array('resident_id');
                $aryMessageListTmp = message_box::retrieveList(message_box::$table, $aryMessageCondition, 1, -999999, $aryMessageSelect);
                $aryMessageList = array();
                foreach($aryMessageListTmp as $aryMessage){
                    $aryMessageList[$aryMessage['resident_id']] = true;
                }
                
                $arySendOut = array();
                $arySendOut['success'] = array();
                $arySendOut['ignored'] = array();
                foreach($aryNeighbourhood['neighbourhood_resident_list'] as $aryResident){
                    if(!$aryMessageList[$aryResident['id']]){
                        message_box::put($aryResident['id'], $strMessage, array(), true, $strFlag);
                        $arySendOut['success'][] = $aryResident['id'];
                    }else{
                        $arySendOut['ignored'][] = $aryResident['id'];
                    }
                }
                
                $objExport->html = 'Successed: '.sizeof($arySendOut['success']).', Ignored: '.sizeof($arySendOut['ignored']);
                break;
            case 'retrieve_available_plot':
                $aryMapMatrixIdList = db_mapmatrix::retrieveMapMatrixForOneNeighbourhood((int)$_POST['neighbourhood_id']);
                $aryMapMatrixOptionList = array();
                $blnSuccess = true;
                $aryCurrentMapMatrixList = array();
                if(is_array($aryMapMatrixIdList) && sizeof($aryMapMatrixIdList) > 0){
                    foreach($aryMapMatrixIdList as $aryMapMatrixId){
                        $aryMapMatrix = db_mapmatrix::retrieveOneMapMatrix($aryMapMatrixId['id']);
                        if($aryMapMatrix['map_element_type_id']==1){
                            $blnAvailable = true;
                            $blnCurrent = false;
                            foreach($aryMapMatrix['building_element_list'] as $aryResidentFieldBuilding){
                                switch($aryResidentFieldBuilding['z_index']){
                                    case 51:
                                    case 111:
                                    case 211:
                                        $blnAvailable = false;
                                        break 2;
                                    case 251:
                                        $blnCurrent = $aryMapMatrixId['id'];
                                        $aryCurrentMapMatrixList[] = $aryMapMatrixId['id'];
                                        break 2;
                                    default:
                                        break;
                                }
                            }
                            if($blnAvailable){
                                $aryMapMatrixOptionList[$aryMapMatrixId['id']] = 'x='.$aryMapMatrix['x'].',y='.$aryMapMatrix['y'].($blnCurrent?'(current)':'');
                            }
                        }
                    }
                }else{
                    $blnSuccess = false;
                }
                $objExportHTML = new smarty_string();
                $objExportHTML->assign('neighbourhood_id', (int)$_POST['neighbourhood_id']);
                $objExportHTML->assign('map_list', $aryMapMatrixOptionList);
                $objExportHTML->assign('current_map_matrix_id', array_pop($aryCurrentMapMatrixList));
                $objExport->html = $objExportHTML->fetch('<select name="map_matrix_id" class="sel_map_matrix_id" id="map_matrix_id${$neighbourhood_id}"><option value="">N/A</option>{html_options options=$map_list selected=$current_map_matrix_id}</select>');
                break;
            case 'update_celebrity_house':
                $blnSuccess = true;
                $aryResidentFieldBuildingCondition = array();
                $aryResidentFieldBuildingCondition['map_matrix_id'] = $_REQUEST['map_matrix_id'];
                $aryResidentFieldBuildingSelect = array('id', 'resident_element_id', 'z_index');
                $aryResidentFieldBuildingList = db_resident_field_building::retrieveList(db_resident_field_building::strTable, $aryResidentFieldBuildingCondition, 0, -9999, $aryResidentFieldBuildingSelect);
                
                $aryMapMatrixIdListTmp = db_mapmatrix::retrieveMapMatrixForOneNeighbourhood($_REQUEST['neighbourhood_id']);
                $aryMapMatrixIdList = array();
                foreach($aryMapMatrixIdListTmp as $aryMapMatrixId){
                    $aryMapMatrixIdList[] = $aryMapMatrixId['id'];
                }
                
                $aryCurrentResidentFieldBuildingCondition = array();
                $aryCurrentResidentFieldBuildingCondition['resident_element_id'] = $_REQUEST['resident_element_id'];
                $aryCurrentResidentFieldBuildingCondition['map_matrix_id'] = $aryMapMatrixIdList;
                $aryCurrentResidentFieldBuildingSelect = array('id', 'resident_element_id', 'map_matrix_id', 'z_index');
                $aryCurrentResidentFieldBuildingList = db_resident_field_building::retrieveList(db_resident_field_building::strTable, $aryCurrentResidentFieldBuildingCondition, 0, -9999, $aryCurrentResidentFieldBuildingSelect);
            
                $aryResidentElementCondition = array();
                $aryResidentElementCondition['id'] = $_REQUEST['resident_element_id'];
                $aryResidentElementSelect = array('id', 'name', 'credit', 'reward_rating', 'resident_element_type_id', 'file_name');
                $aryResidentElement = db_resident_field_building::retrieveList(db_resident_field_building::strResidentElementTable, $aryResidentElementCondition, 0, 0, $aryResidentElementSelect);
            
                $aryResidentElementTypeCondition = array();
                $aryResidentElementTypeCondition['id'] = $aryResidentElement['resident_element_type_id'];
                $aryResidentElementTypeSelect = array('id', 'name', 'x', 'y', 'available', 'map_element_type_id');
                $aryResidentElementType = db_resident_field_building::retrieveList(db_resident_field_building::strResidentElementTypeTable, $aryResidentElementTypeCondition, 0, 0, $aryResidentElementTypeSelect);
                
                $aryResidentElementFileSettings = db_resident_field_building::parseFilename($aryResidentElement['file_name']);
                
                $aryChildElementZIndexList = array();
                if(isset($aryResidentElementFileSettings['query']['part']) && is_array($aryResidentElementFileSettings['query']['part'])){
                    foreach($aryResidentElementFileSettings['query']['part'] as $aryPartSetting){
                        $aryChildElementZIndexList[$aryPartSetting[4]] = true;
                    }
                }
                foreach($aryResidentFieldBuildingList as $aryResidentFieldBuilding){
                    if(floor($aryResidentFieldBuilding['z_index']/10) == floor($aryResidentElement['resident_element_type_id']/10)){
                        $aryData['rmove_list'][] = $aryResidentFieldBuilding['id'];
                    }
                    if($aryChildElementZIndexList[$aryResidentFieldBuilding['z_index']]){
                        $aryData['rmove_list'][] = $aryResidentFieldBuilding['id'];
                    }
                    if($aryResidentFieldBuilding['z_index'] > 10 && $aryResidentFieldBuilding['z_index'] < 111){
                        $aryData['rmove_list'][] = $aryResidentFieldBuilding['id'];
                    }
                }
                
                $aryRefreshMapMatrixIdList = array();
                foreach($aryCurrentResidentFieldBuildingList as $aryCurrentResidentFieldBuilding){
                    $aryData['rmove_list'][] = $aryCurrentResidentFieldBuilding['id'];
                    $aryRefreshMapMatrixIdList[] = $aryCurrentResidentFieldBuilding['map_matrix_id'];
                    
                    $objMapMatrix = new db_mapmatrix();
                    $objMapMatrix->setId($aryCurrentResidentFieldBuilding['map_matrix_id']);
                    $objMapMatrix->name = '';
                    $objMapMatrix->nameMapItem();
                }
                
                $aryRefreshMapMatrixIdList[] = (int)$_REQUEST['map_matrix_id'];
                
                if($blnSuccess){
                    //2. Update map resident id
                    $objResidentFieldBuilding = new db_resident_field_building();
                    $objResidentFieldBuilding->map_matrix_id = $_REQUEST['map_matrix_id'];
                    $objResidentFieldBuilding->resident_element_id = $_REQUEST['resident_element_id'];
                    $objResidentFieldBuilding->mirror = $_REQUEST['mirror'];
                    $objResidentFieldBuilding->timestamp = date('Y-m-d H:i:s');
                    $objResidentFieldBuilding->enabled   = 1;
                    if(is_array($aryData['rmove_list']) && sizeof($aryData['rmove_list']) > 0){
                        //remove both Preset and previouse elements, and related tasks
                        $objResidentFieldBuilding->removeElement($aryData['rmove_list']);
                    }
                    $objResidentFieldBuilding->addResidentBuilding($aryResidentElement, $aryResidentElementType);
                    
                    $objMapMatrix = new db_mapmatrix();
                    $objMapMatrix->setId($_REQUEST['map_matrix_id']);
                    $objMapMatrix->name = $aryResidentElement['name'];
                    $objMapMatrix->nameMapItem();
                    
                    foreach($aryRefreshMapMatrixIdList as $intRefreshMapMatrixId){
                        db_resident_field_building::retrieveBuildingListForOneMapMatrix($intRefreshMapMatrixId, true);
                    }
                }
                
                $objExport->html = 'Done';
                break;
            default:
                break;
        }
		$objExport->valid = $blnSuccess;
		$objExport->text = $strText;
		echo Zend_Json::encode ( $objExport );
        break;
	case 'home':
		global $CFG;
		$objExport = new stdClass();
		$objHTML = new smarty_admin ( );

        $objCache = Zend_Cache::factory('Core', 'File', cache::aryFontendOptions(null), cache::aryBackendOptions());
        switch($_POST ['field_name']){
            case 'most_20_referrals_email':
                $objExport->data->status = 'true';
                list($aryRegisterList) = register::retrieveRegisterListByConditionAndPageNumber(array(), 1, 20, array('ref_qty DESC'), array(), false, true);
                $objCache->save($aryRegisterList, 'most_20_referrals_email');
                break;
            case 'most_20_referrals_register':
                $objExport->data->status = 'true';
                list($aryRegisterList) = register::retrieveRegisterListByConditionAndPageNumber(array(), 1, 20, array('ref_qty_true DESC'), array(), false, true);
                $objCache->save($aryRegisterList, 'most_20_referrals_register');
                break;
            default:
                $objExport->data->status = 'false';
                break;
        }
        
		$objHTML->assign('CFG', $CFG);
		$objHTML->assign('ary_register_list', $aryRegisterList);
		$objExport->data->html = $objHTML->fetch ( 'home_field.tpl' );
        $objExport->data->id = $objExport->data->status == 'true' ? $_POST ['field_name'] : '';
		$objExport->text = 'Data Retrieved!';
		echo Zend_Json::encode ( $objExport );
		break;
	case 'send_register_email' :
		$objRegister = new register ( );
		$objRegister->setId($register_id);
		$objRegister->retrieveRegisterById();
		if($objRegister->retrieveId()){
			$objRegister->submitWinnerEmail();
			$objExport = new stdClass();
			$objExport->text = 'Data Saved!';
			$objExport->data->valid = true;
		}else{
			$objExport->text = 'Wrong Register!';
			$objExport->data->valid = false;
		}
		
		echo Zend_Json::encode ( $objExport );
		break;
	case 'edit_register' :
		$objExport = new stdClass();
		
		$objRegister = new register ( );
		$objRegister->setId($register_id);
		
		$objRegister->retrieveRegisterById();
		
		if($objRegister->retrieveId()){
			switch ($objRegister->type){
				case 'campaign':
					$aryFieldList = array_merge(register::$aryCompaignFormField, register::$aryCompaignExtraFormField);
			
					if(isset($_POST ['save'])){
						list($aryPostCampaign, $aryMsgCampaign) = register::validatFormForCampaign($_POST);
						list($aryPostExtraCampaign, $aryMsgExtraCampaign) = register::validatExtraFormForCampaign($_POST);
						$aryPost = array_merge($aryPostCampaign, $aryPostExtraCampaign);
						$aryMsg = array_merge($aryMsgCampaign, $aryMsgExtraCampaign);
						
						if(!in_array($_POST['status'], register::$aryStatus)){
							$aryMsg['status'] = 'error';
							$aryPost['status'] = current(register::$aryStatus);
						}else{
							$aryPost['status'] = $_POST['status'];
						}
						
						if (is_array($aryMsg) && sizeof($aryMsg) == 0){
							$objRegister->first_name		= $aryPostCampaign['first_name'];
							$objRegister->last_name			= $aryPostCampaign['last_name'];
							$objRegister->address			= $aryPostCampaign['address'];
							$objRegister->state				= $aryPostCampaign['state'];
							$objRegister->postcode			= '';
							$objRegister->barcode			= $aryPostCampaign['barcode'];
							$objRegister->receipt			= $aryPostCampaign['receipt'];
							$objRegister->phone				= $aryPostCampaign['phone'];
							$objRegister->email				= $aryPostCampaign['email'];
							$objRegister->reminder			= $aryPostCampaign['reminder'];
							$objRegister->promotions		= $aryPostCampaign['promotions'];
							
							$objRegister->daily_prizes		= $aryPostExtraCampaign['daily_prizes'];
							$objRegister->cashback			= $aryPostExtraCampaign['cashback'];
			
							$objRegister->status			= $aryPost['status'];
							$objRegister->updateRegister();
							
							$objExport->text = 'Data Saved!';
							$objExport->data->valid = true;
						}
					}
					break;
				case 'reminder':
					$aryFieldList = register::$aryReminderFormField;
					if(isset($_POST ['save'])){
						list($aryPost, $aryMsg) = register::validatFormForReminder($_POST);
						
						if(!in_array($_POST['status'], register::$aryStatus)){
							$aryMsg['status'] = 'error';
							$aryPost['status'] = current(register::$aryStatus);
						}else{
							$aryPost['status'] = $_POST['status'];
						}
						
						if (is_array($aryMsg) && sizeof($aryMsg) == 0){
							$objRegister->first_name		= $aryPost['first_name'];
							$objRegister->last_name			= $aryPost['last_name'];
							$objRegister->address			= $aryPost['address'];
							$objRegister->state				= $aryPost['state'];
							$objRegister->postcode			= $aryPost['postcode'];
							$objRegister->barcode			= '';
							$objRegister->phone				= $aryPost['phone'];
							$objRegister->email				= $aryPost['email'];
							$objRegister->reminder			= $aryPost['reminder'];
							$objRegister->promotions		= 'deny';
							
							$objRegister->daily_prizes		= 'no';
							$objRegister->cashback			= 'no';
							
							$objRegister->status			= $aryPost['status'];
							$objRegister->updateRegister();
							
							$objExport->text = 'Data Saved!';
							$objExport->data->valid = true;
						}
					}
					break;
			}
			
		}
		
		if (!isset($aryMsg) || !is_array($aryMsg) || sizeof($aryMsg) > 0) {
			$objHTML = new smarty_admin ( );
			if(isset($aryMsg)){
				$objHTML->assign ( 'msg', $aryMsg );
			}
			$objRegister->first_name		= $aryPost['first_name']?$aryPost['first_name']:$objRegister->first_name;
			$objRegister->last_name			= $aryPost['last_name']?$aryPost['last_name']:$objRegister->last_name;
			$objRegister->address			= $aryPost['address']?$aryPost['address']:$objRegister->address;
			$objRegister->state				= $aryPost['state']?$aryPost['state']:$objRegister->state;
			$objRegister->postcode			= $aryPost['postcode']?$aryPost['postcode']:$objRegister->postcode;
			$objRegister->barcode			= $aryPost['barcode']?$aryPost['barcode']:$objRegister->barcode;
			$objRegister->phone				= $aryPost['phone']?$aryPost['phone']:$objRegister->phone;
			$objRegister->email				= $aryPost['email']?$aryPost['email']:$objRegister->email;
			$objRegister->reminder			= $aryPost['reminder']?$aryPost['reminder']:$objRegister->reminder;
			$objRegister->promotions		= $aryPost['promotions']?$aryPost['promotions']:$objRegister->promotions;
			
			$objRegister->daily_prizes		= $aryPost['daily_prizes']?$aryPost['daily_prizes']:$objRegister->daily_prizes;
			$objRegister->cashback			= $aryPost['cashback']?$aryPost['cashback']:$objRegister->cashback;
			$objRegister->status			= $aryPost['status']?$aryPost['status']:$objRegister->status;
			
			$aryFieldList[] = 'status';
			
			$objHTML->assign ( 'register_id', $objRegister->retrieveId() );
			$objHTML->assign ( 'state_list', register::$aryState );
			$objHTML->assign ( 'agreement_list', register::$aryAgreement );
			$objHTML->assign ( 'accept_list', register::$aryAccept );
			$objHTML->assign ( 'status_list', register::$aryStatus );
			$objHTML->assign ( 'field_list', $aryFieldList );
			$objHTML->assign ( 'obj_register', $objRegister );
			if (isset ( $aryMsg )) {
				$objExport->text = 'Wrong Data!';
				$objExport->data->valid = false;
				$objHTML->assign ( 'msg', $aryMsg );
			}else{
				$objExport->data->valid = true;
			}
			$objExport->data->html = $objHTML->fetch ( 'register_form.tpl' );
		}
		$objExport->data->msg = $aryMsg;
		$objExport->data->id = $objRegister->retrieveId();
		echo Zend_Json::encode ( $objExport );
		break;
	case 'pick_winners':
		global $CFG;
		
		$objExport = new stdClass();
		$objHTML = new smarty_admin ( );
		$aryRegisterList = register::pick5winner();
		
		$aryPageContent = array();
		$aryPageContent['register_list'] = $aryRegisterList;
		$aryPageContent['page_mode'] = 'campaign';
		
		$objHTML->assign('CFG', $CFG);
		$objHTML->assign('page_content', $aryPageContent);
		$objExport->data->html = $objHTML->fetch ( 'winner_table.tpl' );
		$objExport->text = 'Data Retrieved!';
		echo Zend_Json::encode ( $objExport );
		break;
	case 'update_winners' :
		
		$aryWinnerId = array_values($aryData);
		$strWinnerDate = date('Y-m-d');
		register::updateDailyWinner($aryWinnerId, $strWinnerDate);
		
		$objExport = new stdClass();
		$objExport->text = 'Data Saved!';
		$objExport->data->valid = true;
		
		echo Zend_Json::encode ( $objExport );
		break;
	default :
		$objExport->text = 'Command does not exist!';
		echo Zend_Json::encode ( $objExport );
		break;
}

exit ();
?>