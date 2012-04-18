{if  $USER->ACL->admin.view}
<style>
/*{literal}*/
body.admin div.custom_field > div.page_content table.image_list > tbody > tr.status_successed{
	background-color: #ddf2f2;
}
body.admin div.custom_field > div.page_content table.image_list > tbody > tr.status_failed{
	background-color: #e2cccc;
}
body.admin div.custom_field > div.page_content table.image_list > tbody > tr.status_eway_error{
	background-color: #e2aaaa;
}
/*{/literal}*/
</style>
<div class="content custom_field">
    <div class="page_content">
        <h2>Account Info :: {$page_content.ary_resident.name}<a id="page_detail" name="page_detail"></a></h2>
        <table class="image_list" cellpadding="2" cellspacing="2" border="0">
			<thead>
			<tr>
                <th align="center">&nbsp;</th>
				<th align="center">Name</th>
				<th align="center">eMail</th>
				<th align="center" width="60">Credits</th>
				<th align="center" width="80">Notification</th>
				<th align="center">Current Login</th>
				<th align="center">Current IP</th>
				<th align="center">Last Login</th>
				<th align="center">Last IP</th>
				<th align="center" width="102">Created</th>
			</tr>
			</thead>
			<tbody>
			{assign var='ary_register_item' value=$page_content.ary_resident}
			<tr id="register_row_{$ary_register_item.id}" class="{cycle values=",alt"}">
				<td align="center"><a href="http://www.facebook.com/profile.php?id={$ary_register_item.facebook_id}" target="facebook"><img src="{$CFG->www}images/facebook.gif" width="16" height="16" alt="facebook profile" /></a></td>
				<td>{$ary_register_item.name}</td>
				<td>{$ary_register_item.email}</td>
				<td align="center">{$ary_register_item.credit}</td>
				<td align="center">{$ary_register_item.notification}</td>
				<td align="center">{$ary_register_item.currentlogin}</td>
				<td align="center">{$ary_register_item.currentip}</td>
				<td align="center">{$ary_register_item.lastlogin}</td>
				<td align="center">{$ary_register_item.lastip}</td>
				<td align="center">{$ary_register_item.created}</td>
			</tr>
			</tbody>
		</table>
    </div>
    <div class="page_content">
        <h2>Neighbourhood Info</h2>
		<table class="image_list" cellpadding="2" cellspacing="2" border="0">
		{if $page_content.ary_neighbourhood_info_list|@is_array && $page_content.ary_neighbourhood_info_list|@sizeof}
			<thead>
			<tr>
				<th align="center" width="200">Name</th>
				<th align="center" width="100">Level</th>
				<th align="center">Rating</th>
				<th align="center" width="150">Mayer</th>
				<th align="center" width="150">Plots Qty</th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$page_content.ary_neighbourhood_info_list item=ary_neighbourhood_info_item}
			<tr id="register_row_{$ary_neighbourhood_info_item.neighbourhood_id}" class="{cycle values=",alt"}">
				<td>{$ary_neighbourhood_info_item.name}</td>
				<td align="center">{$ary_neighbourhood_info_item.level}</td>
				<td align="center">{$ary_neighbourhood_info_item.rating}</td>
				<td align="center">{if $ary_neighbourhood_info_item.position_id}<strong>- Yes -</strong>{else}No{/if}</td>
				<td align="center">{$ary_neighbourhood_info_item.map_item_list|@sizeof}</td>
			</tr>
			{/foreach}
			</tbody>
        {else}
			<tr>
				<th align="center">N/A</th>
			</tr>
		{/if}
		</table>
    </div>
    <div class="page_content">
        <h2>Cuppon History</h2>
		<table class="image_list" cellpadding="2" cellspacing="2" border="0">
		{if $page_content.ary_cuppon_history_list|@is_array && $page_content.ary_cuppon_history_list|@sizeof}
			<thead>
			<tr>
				<th align="center" width="200">Cuppon Code</th>
				<th align="center" width="100">Credit</th>
				<th align="center">Partner</th>
				<th align="center" width="300">Timestamp</th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$page_content.ary_cuppon_history_list item=ary_cuppon_history_item}
			<tr id="register_row_{$ary_cuppon_history_item.cuppon_id}" class="{cycle values=",alt"}">
				<td align="center">{$ary_cuppon_history_item.cuppon_code}</td>
				<td align="center">{$ary_cuppon_history_item.credit}</td>
				<td>{$ary_cuppon_history_item.partner}</td>
				<td align="center">{$ary_cuppon_history_item.timestamp}</td>
			</tr>
			{/foreach}
			</tbody>
        {else}
			<tr>
				<th align="center">N/A</th>
			</tr>
		{/if}
		</table>
    </div>
    <div class="page_content">
        <h2>Order History</h2>
		{if $page_content.order_list|@is_array && $page_content.order_list|@sizeof}
		<div class="tools">
			<ul>
				<li><a class="tool_icon" href="?section=resident_info_csv&amp;resident_id={$ary_register_item.id}&amp;mode={$page_content.page_mode}&amp;last_download=all">Retrieve as CSV [All Entries][{$page_content.int_csv_total}]</a></li>
				<li><a class="tool_icon" href="javascript:fnSuccessOrderOnly()" id="btnSuccessOrderOnly">Success Order Only</a></li>
			</ul>
			<div class="clear"><br class="accessibility"/></div>
		</div>
		{include file='paging.tpl' page_list=$page_content.page_list select_page=$page_content.page int_paging_range=9 page_title_list=$page_content.page_title_list href='?section='|@mergeString:$CFG->section:'&amp;mode=info&amp;resident_id=':$ary_register_item.id:'&amp;order=':$page_content.order:'&amp;asc=':$page_content.asc:'&amp;data=':$page_content.data:'&amp;search_field=':$page_content.search_field:'&amp;'}
		<div>
			<form class="normal_form" name="image_search" method="get" action="" >
			<input type="hidden" id="section" name="section" value="{$CFG->section}" />
			<input type="hidden" id="mode" name="mode" value="info" />
			<input type="hidden" id="resident_id" name="resident_id" value="{$ary_register_item.id}" />
			<input type="hidden" id="page" name="page" value="{$page_content.page}" />
			<input type="hidden" id="order" name="order" value="{$page_content.order}" />
			<input type="hidden" id="asc" name="asc" value="{$page_content.asc}" />
				<div align="right">
					<input type="text" name="data" id="data" value="{$page_content.data}" />
					<select name="search_field" id="search_field">
						<option value="">N/A</option>
						{html_options options=$page_content.aryFieldName selected=$page_content.search_field}
					</select>
					<input type="submit" name="search" id="search" value="Search"/>
				</div>
			</form>
		</div>
		<form class="normal_form" name="image_form" method="post" action="" >
			<input type="hidden" id="section" name="section" value="{$CFG->section}" />
			<input type="hidden" id="page" name="page" value="{$page_content.page}" />
			<input type="hidden" name="register_date" value="{$page_content.selected_date}" />
		<table class="image_list" cellpadding="2" cellspacing="2" border="0">
			<thead>
			<tr>
				<th align="center">Name<br/><span>[<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=LastName&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">A-Z</a>] [<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=LastName&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">Z-A</a>]</span></th>
				<th align="center">eMail<br/><span>[<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=Email&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">A-Z</a>] [<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=Email&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">Z-A</a>]</span></th>
				<th align="center">Card Holders Name<br/><span>[<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=CardHoldersName&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">1</a>] [<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=CardHoldersName&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9</a>]</span></th>
				<th align="center" width="80">Card Masked Number<br/><span></span></th>
				<th align="center">PlanName<br/><span>[<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=PlanName&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">1</a>] [<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=PlanName&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9</a>]</span></th>
				<th align="center" width="102">Time Stamp<br/><span>[<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=timestamp&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">1</a>] [<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=timestamp&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9</a>]</span></th>
				<th align="center" width="100">Transaction No.<br/><span></span></th>
				<th align="center">Status<br/><span>[<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=order_status&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">A</a>] [<a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}&amp;page={$page_content.page}&amp;order=order_status&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">Z</a>]</span></th>
				<th align="center">&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$page_content.order_list item=ary_order_item}
			<tr id="register_row_{$ary_order_item.id}" class="status_{$ary_order_item.order_status|@strtolower|replace:" ":"_"}">
				<td>{$ary_order_item.FirstName}, {$ary_order_item.LastName}</td>
				<td>{$ary_order_item.Email}</td>
				<td>{$ary_order_item.CardHoldersName}</td>
				<td>{$ary_order_item.CardMaskedNumber}</td>
				<td align="center">{$ary_order_item.PlanName}</td>
				<td align="center">{$ary_order_item.timestamp}</td>
				<td align="center">{$ary_order_item.trans_no}</td>
				<td align="center">{$ary_order_item.order_status}</td>
				<td align="center" title="{$ary_order_item.eway_return_msg|@unserialize|@print_r:1}" style="cursor:pointer"><strong>?</strong></td>
			</tr>
			{/foreach}
			</tbody>
		</table>
		</form>
        {else}
        <table class="image_list" cellpadding="2" cellspacing="2" border="0">
			<tr>
				<th align="center">N/A</th>
			</tr>
		</table>
		{/if}
    </div>
</div>
<script language="javascript" type="text/javascript">
	var www = '{$CFG->www}';
	{literal}
	function fnSuccessOrderOnly(){
	   if($('#btnSuccessOrderOnly').hasClass('selected')){
    		$('tr.status_failed').each(function(){
    		  $(this).show();
    		});
    		$('tr.status_eway_error').each(function(){
    		  $(this).show();
    		});
            $('#btnSuccessOrderOnly').removeClass('selected');
	   }else{
    		$('tr.status_failed').each(function(){
    		  $(this).hide();
    		});
    		$('tr.status_eway_error').each(function(){
    		  $(this).hide();
    		});
            $('#btnSuccessOrderOnly').addClass('selected');
	   }
       
       
	}

	function fnUpdateWinners(form){

	  	form.submit.readonly = true;
	
	  	var id_list = new Array();
	  	if(typeof form['sub_winner[]'].value == 'undefined'){
				for(var i = 0; i < form['sub_winner[]'].length; i++){
					id_list.push(form['sub_winner[]'][i].value);
				}
	  	}else{
	  		id_list.push(form['sub_winner[]'].value);
	  	}
  	
		var data = {};
		data.mode	= 'update_winners';
		data.save	= true;
		data.data	= id_list.join(',');
		
		fnHidePanel();
		fnAjaxWithInfo(data, 'location.reload');
	}
	
	function fnRandomWinner(radio_list){
		if(typeof radio_list.length == 'undefined'){
			radio_list.checked = 'checked';
			return;
		}
		var data_length = radio_list.length.toString().length;
		var range = Math.random() * data_length * 10;
		var selected = Math.ceil(range*radio_list.length/(data_length*10-1)) - 1;
		radio_list[selected].checked = 'checked';
		return;
	}
	
	function fnSendWinnerEmail(form){
		if(typeof form.is_winner == 'undefined'){
			alert('No winner for the day!');
			return;
		}
		
		var currentWinnerId = form.is_winner.value;
		
		if(typeof form.saved_winner == 'undefined'){
			alert('Please save winner first!');
			return;
		}
		
		var savedWinnerId = form.is_winner.value;

		if(currentWinnerId != savedWinnerId || !(currentWinnerId > 0)){
			alert('Please save winner first!');
			return;
		}


		var text = $('#register_row_'+currentWinnerId).text();
		$('#register_row_'+currentWinnerId)[0].focus();
		
		var answer = confirm("Would you like to send email to:\n"+text.trim().replace(/(\s)+/g,',').replace(/\,+/g,','));

		if (answer){
			var data = {};
			data.mode	= 'send_register_email';
			data.id		= currentWinnerId;
			fnAjaxWithInfo(data, '');
		}
		return;
	}
	
	function fnUpdateRegister(id){
		var data = {};
		data.mode	= 'edit_register';
		data.id		= id;
		fnAjaxWithInfo(data, 'fnDisplayPanel');
	}
	
  	function fnSubmitForm(form){
  		form.submit.readonly = true;

		var data = {};
		data.mode	= 'edit_register';
		data.save	= true;
		data.id		= form.id.value;

		var ary_field_list = form.field_list.value.toString().split(',');
		for(var i = 0; i < ary_field_list.length; i++){
			data[ary_field_list[i]]	= form[ary_field_list[i]].value;
		}
		
		fnHidePanel();
		fnAjaxWithInfo(data, 'fnRetrieveForm');
  	}
  	
  	function fnRetrieveForm(data){
		if(data && (typeof data != 'undefined')){
			if(data.valid == true){
				fnClosePanel();
				//retrieve panel
				location.reload();
			}else{
				fnShowPanel();
			}
		}
  	}
	{/literal}
      
</script>
{/if}