<?php
require_once('marquee.class.php');
$CFG->section = 'marquee';

$strAction = $_POST['action'];

switch ($strAction){
	case 'maintain':
		list($aryPost, $aryMsg) = marquee::validatForm($_POST);
		
		$objHTML = new smarty_mod();
		$page_content['panel'] = new stdClass();
		
		$objMarquee = new marquee();
		if(is_array($aryMsg) && sizeof($aryMsg)==0){
			//update or add
			
			$objMarquee->content	= $aryPost['content'];
			$objMarquee->updateMarquee();
			
			$objExport->text = 'Data Saved!';
			$objExport->data->valid = true;
		}else{
			foreach ($aryPost as $var => $value){
				$page_content['marquee']->$var = $value;
			}
			$objHTML->assign('vlidation', $aryMsg);
			$objExport->text = 'Wrong Data!';
			$objExport->data->valid = false;
		}
		$objHTML->assign('page_content', $page_content);
		$objExport->data->html = $objHTML->fetch('marquee.maintain_marquee.tpl');
		
		echo json_encode($objExport);
		break;
	case 'retrive':
		
		$objMarquee = new marquee();
		$objMarquee->retriveMarquee();
		
		$objHTML = new smarty_mod();
		
		global $CFG;
		$objHTML->assign('CFG', $CFG);
		$objHTML->assign('obj_marquee_item', $objMarquee);
		
		$objExport->text = 'Data Retrived!';
		$objExport->data->html = $objHTML->fetch('marquee.marquee_list.tpl');
		
		echo json_encode($objExport);
		break;
	case 'edit_ui':
		$objMarquee = new marquee();
		$objMarquee->retriveMarquee();
		
		$objHTML = new smarty_mod();
		$page_content['marquee'] = $objMarquee;
		$objHTML->assign('page_content', $page_content);
		
		$objExport->data->html = $objHTML->fetch('marquee.maintain_marquee.tpl');
		$objExport->data->id = 1;
		echo json_encode($objExport);
		break;
}
?>