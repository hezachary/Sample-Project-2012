{if  $USER->ACL->admin.view}
<div class="content custom_field">
    <div class="page_content">
        <h2>{$CFG->section|replace:"_":" "|capitalize:true}<a id="page_detail" name="page_detail"></a></h2>
		<div class="tools">
			<ul>
				<li><a class="tool_icon" href="?section=csv&amp;mode={$page_content.page_mode}&amp;search_field=created&amp;data={'today~'|@urlencode}">Retrieve Today CSV [{$page_content.int_csv_today}]</a></li>
				<li><a class="tool_icon" href="?section=csv&amp;mode={$page_content.page_mode}&amp;search_field=created&amp;data={'yesterday~yesterday'|@urlencode}">Retrieve Yesterday CSV[{$page_content.int_csv_yesterday}]</a></li>
				<li><a class="tool_icon" href="?section=csv&amp;mode={$page_content.page_mode}">Retrieve Total CSV [{$page_content.int_csv_total}]</a></li>
				{if $page_content.page_mode=='campaign'}
				<li><a class="tool_icon" href="javascript:fnPickWinners()">Pick 5 Winners</a></li>
				{/if}
			</ul>
			<div class="clear"><br class="accessibility"/></div>
		</div>
		{include file='paging.tpl' page_list=$page_content.page_list select_page=$page_content.page int_paging_range=9 page_title_list=$page_content.page_title_list href='?section='|@mergeString:$CFG->section:'&amp;order=':$page_content.order:'&amp;asc=':$page_content.asc:'&amp;data=':$page_content.data:'&amp;search_field=':$page_content.search_field:'&amp;'}
		<div>
			<form class="normal_form" name="image_search" method="get" action="" >
			<input type="hidden" id="section" name="section" value="{$CFG->section}" />
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
		{if $page_content.resident_list|@is_array && $page_content.resident_list|@sizeof}
			<thead>
			<tr>
                <th align="center">&nbsp;</th>
				<th align="center">Name<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=name&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">A-Z</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=name&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">Z-A</a>]</span></th>
				<th align="center">eMail<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=email_address&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">A-Z</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=email_address&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">Z-A</a>]</span></th>
				<th align="center" width="60">Credits<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=credit&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">1</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=credit&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9</a>]</span></th>
				<th align="center" width="80">Notification<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=notification&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">1</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=notification&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9</a>]</span></th>
				<th align="center">Current Login<br/><span></span></th>
				<th align="center">Current IP<br/><span></span></th>
				<th align="center">Last Login<br/><span></span></th>
				<th align="center">Last IP<br/><span></span></th>
				<th align="center" width="102">Created<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=created&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=created&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$page_content.resident_list item=ary_register_item}
			<tr id="register_row_{$ary_register_item.id}" class="{cycle values=",alt"}">
				<td align="center"><a href="http://www.facebook.com/profile.php?id={$ary_register_item.facebook_id}" target="facebook"><img src="{$CFG->www}images/facebook.gif" width="16" height="16" alt="facebook profile" /></a></td>
				<td><a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}">{$ary_register_item.name}</a></td>
				<td><a href="?section={$CFG->section}&amp;mode=info&amp;resident_id={$ary_register_item.id}">{$ary_register_item.email}</a></td>
				<td align="center">{$ary_register_item.credit}</td>
				<td align="center">{$ary_register_item.notification}</td>
				<td align="center">{$ary_register_item.currentlogin}</td>
				<td align="center">{$ary_register_item.currentip}</td>
				<td align="center">{$ary_register_item.lastlogin}</td>
				<td align="center">{$ary_register_item.lastip}</td>
				<td align="center">{$ary_register_item.created}</td>
			</tr>
			{/foreach}
			</tbody>
		{/if}
		</table>
		</form>
    </div>
</div>
<script language="javascript" type="text/javascript">
	var www = '{$CFG->www}';
	{literal}
	function fnPickWinners(){
		fnClosePanel();
		var data = {};
		data.mode	= 'pick_winners';
		fnAjaxWithInfo(data, 'fnDisplayPanel');
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