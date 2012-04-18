<?php

function fnPopulateDateData($tmpDailyRegisterRate, $tmpDailyRefEmailLog){
	$intRegTotal = 0;
	$intEmailTotal = 0;
			$strStartDate = strtotime($tmpDailyRegisterRate[0]['log_date']) < strtotime($tmpDailyRefEmailLog[0]['log_date']) ? $tmpDailyRegisterRate[0]['log_date'] : $tmpDailyRefEmailLog[0]['log_date'];
			$strEndDate = strtotime($tmpDailyRegisterRate[sizeof($tmpDailyRegisterRate)-1]['log_date']) > strtotime($tmpDailyRefEmailLog[sizeof($tmpDailyRefEmailLog)-1]['log_date']) ? $tmpDailyRegisterRate[sizeof($tmpDailyRegisterRate)-1]['log_date'] : $tmpDailyRefEmailLog[sizeof($tmpDailyRefEmailLog)-1]['log_date'];
			
			$dateStartDate = strtotime($strStartDate);
			$dateEndDate = strtotime($strEndDate);
			
			$aryRegisterRate = array();
			foreach($tmpDailyRegisterRate as $aryLog){
				$aryRegisterRate[$aryLog['log_date']] = $aryLog;
			}
			$tmpDailyRegisterRate = array();
			
			$aryDailyRefEmailLog = array();
			foreach($tmpDailyRefEmailLog as $aryLog){
				$aryDailyRefEmailLog[$aryLog['log_date']] = $aryLog;
			}
			$tmpDailyRefEmailLog = array();
			
			$intCount = 0;
			$dateCurrentDate = strtotime($strStartDate.' + '.$intCount.' days');
			while($dateCurrentDate <= $dateEndDate){
				$strCurrentDate = date('Y-m-d', $dateCurrentDate);
				if(!$aryRegisterRate[$strCurrentDate]){
					$aryRegisterRate[$strCurrentDate] = array('log_date'=>$strCurrentDate, 'total'=>0);
				}
				$intRegTotal += $aryRegisterRate[$strCurrentDate]['total'];
				$intCount ++;
				$dateCurrentDate = strtotime($strStartDate.' + '.$intCount.' days');
			}
			ksort($aryRegisterRate);
			
			$intCount = 0;
			$dateCurrentDate = strtotime($strStartDate.' + '.$intCount.' days');
			while($dateCurrentDate <= $dateEndDate){
				$strCurrentDate = date('Y-m-d', $dateCurrentDate);
				if(!$aryDailyRefEmailLog[$strCurrentDate]){
					$aryDailyRefEmailLog[$strCurrentDate] = array('log_date'=>$strCurrentDate, 'total'=>0);
				}
				$intEmailTotal += $aryDailyRefEmailLog[$strCurrentDate]['total'];
				$intCount ++;
				$dateCurrentDate = strtotime($strStartDate.' + '.$intCount.' days');
			}
			ksort($aryDailyRefEmailLog);
			return array($aryRegisterRate, $aryDailyRefEmailLog, $intRegTotal, $intEmailTotal);
}

function fnFindTotal($aryData, $intStartDate = null, $intEndDate = null){
	$intTotal = 0;
	reset($aryData);
	switch(true){
		case ($intStartDate && $intEndDate):
			foreach($aryData as $aryRow){
				if(strtotime($aryRow['log_date']) >= $intStartDate && strtotime($aryRow['log_date']) <= $intEndDate){
					$intTotal += $aryRow['total'];
				}
			}
			break;
		case (!$intStartDate && $intEndDate):
			foreach($aryData as $aryRow){
				if(strtotime($aryRow['log_date']) <= $intEndDate){
					$intTotal += $aryRow['total'];
				}
			}
			break;
		case ($intStartDate && !$intEndDate):
			foreach($aryData as $aryRow){
				if(strtotime($aryRow['log_date']) >= $intStartDate){
					$intTotal += $aryRow['total'];
				}
			}
			break;
		case (!$intStartDate && !$intEndDate):
			foreach($aryData as $aryRow){
				$intTotal += $aryRow['total'];
			}
			break;
	}
	return $intTotal;
}


function fnRetrieveSearchField($aryCondition, $aryRequest, $strTable){
    
	if(!empty($aryRequest['search_field'])){
		if(strpos($aryRequest['data'], '~')>0){
			$aryData = explode('~', $aryRequest['data']);
			$timeData_0 = strtotime($aryData[0]);
			$timeData_1 = strtotime($aryData[1]);
			if($timeData_1 < $timeData_0 && $timeData_1){
				$strData_tmp = $aryData[0];
				$aryData[0] = $aryData[1];
				$aryData[1] = $strData_tmp;
				
				$timeData_tmp = $timeData_0;
				$timeData_0 = $timeData_1;
				$timeData_1 = $timeData_tmp;
			}
			if($timeData_0 > 0){
				$aryCondition[$aryRequest['search_field'].'_0'][] = array($strTable.'.'.$aryRequest['search_field'], '>=', date('Y-m-d H:i:s', $timeData_0));
			}
			if($timeData_1 > 0){
				$blnSetTimeField = false;
				$strTime = '';
				if(date('s', $timeData_1) == 0 && $blnSetTimeField == false){
					$strTime = '+1 minute';
				}else{
					$blnSetTimeField = true;
				}
				
				if(date('i', $timeData_1) == 0 && $blnSetTimeField == false){
					$strTime = '+1 hour';
				}else{
					$blnSetTimeField = true;
				}
				
				if(date('H', $timeData_1) == 0 && $blnSetTimeField == false){
					$strTime = '+1 day';
				}else{
					$blnSetTimeField = true;
				}
				
				if(date('d', $timeData_1) == 0 && $blnSetTimeField == false){
					$strTime = '+1 month';
				}else{
					$blnSetTimeField = true;
				}
				
				if(date('m', $timeData_1) == 0 && $blnSetTimeField == false){
					$strTime = '+1 year';
				}else{
					$blnSetTimeField = true;
				}
				$strTime .= ' -1 second';
				$aryCondition[$aryRequest['search_field'].'_1'][] = array($strTable.'.'.$aryRequest['search_field'], '<=', date('Y-m-d H:i:s', strtotime($aryData[1].' '.$strTime)));
			}
		}else{
			switch($aryRequest['data']){
				case 'ISNULL':
					$aryCondition[$strTable.'.'.$aryRequest['search_field']][] = array($strTable.'.'.$aryRequest['search_field'], 'IS', 'NULL');
					break;
				case 'ISNOTNULL':
					$aryCondition[$strTable.'.'.$aryRequest['search_field']][] = array($strTable.'.'.$aryRequest['search_field'], 'IS NOT', 'NULL');
					break;
				default:
					$aryCondition[$strTable.'.'.$aryRequest['search_field']][] = array($strTable.'.'.$aryRequest['search_field'], 'LIKE', $aryRequest['data']);
					break;
			}
		}
		
		$strSearchField = htmlentities($aryRequest['search_field'], ENT_QUOTES);
		$strData = htmlentities( $aryRequest['data'], ENT_QUOTES);
        
        return array($aryCondition, $strSearchField, $strData);
	}
}