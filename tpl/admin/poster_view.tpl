{if  $USER->username}{/if}
<script language="javascript" type="text/javascript" src="{$CFG->www}js/swfaddress.js"></script>
<script language="javascript" type="text/javascript" src="{$CFG->www}js/swfobject.js"></script>
<script language="javascript" type="text/javascript" src="{$CFG->www}js/ParsedQueryString.js"></script>
<script language="javascript" type="text/javascript">
var www = '{$CFG->www}';
{literal}
{/literal}
</script>
<div class="content custom_field">
    <div class="page_content">
        <h2>{$CFG->section|capitalize:true} List<a id="page_detail" name="page_detail"></a></h2>
		<div class="tools">
			<ul>
				<li><a class="tool_icon {if $page_content.status=='pending'}selected{/if}" href="?section={$CFG->section}&amp;mode=pending">Pending Poster(s)</a></li>
				<li><a class="tool_icon {if $page_content.status=='approved'}selected{/if}" href="?section={$CFG->section}&amp;mode=approved">Approved Poster(s)</a></li>
				<li><a class="tool_icon {if $page_content.status=='denied'}selected{/if}" href="?section={$CFG->section}&amp;mode=denied">Denied Poster(s)</a></li>
				<li><a class="tool_icon {if $page_content.status=='failed'}selected{/if}" href="?section={$CFG->section}&amp;mode=failed">Failed Poster(s)</a></li>
				<li><a class="tool_icon {if $page_content.status=='uploading'}selected{/if}" href="?section={$CFG->section}&amp;mode=uploading">Uploading Poster(s)</a></li>
				<li><a class="tool_icon" href="?section=csv_poster&amp;mode={$page_content.status}&amp;last_download=new">Retrieve as CSV [New Entries Only][{$page_content.int_csv_new}]</a></li>
				<li><a class="tool_icon" href="?section=csv_poster&amp;mode={$page_content.status}&amp;last_download=all">Retrieve as CSV [All Entries][{$page_content.int_csv_total}]</a></li>
				{if $page_content.page_mode=='campaign'}
				<li><a class="tool_icon" href="javascript:fnPickWinners()">Pick 5 Winners</a></li>
				{/if}
			</ul>
			<div class="clear"><br class="accessibility"/></div>
		</div>
		{include file='paging.tpl' page_list=$page_content.page_list select_page=$page_content.page int_paging_range=9 page_title_list=$page_content.page_title_list href='?section='|@mergeString:$CFG->section:'&amp;mode=':$page_content.status:'&amp;order=':$page_content.order:'&amp;asc=':$page_content.asc:'&amp;data=':$page_content.data:'&amp;search_field=':$page_content.search_field:'&amp;'}
		<div>
			<form class="normal_form" name="image_search" method="get" action="" >
			<input type="hidden" id="section" name="section" value="{$CFG->section}" />
			<input type="hidden" id="mode" name="mode" value="{$page_content.status}" />
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
			<input type="hidden" name="poster_date" value="{$page_content.selected_date}" />
		<table class="image_list" cellpadding="2" cellspacing="2" border="0">
		{if $page_content.poster_list|@is_array && $page_content.poster_list|@sizeof}
			<thead>
			<tr class="">
				<td colspan="1" align="left">
					<label for="total_poster">Apply to All : </label>
					<select name="total_poster" id="total_poster" onchange="fnApplyAll('poster', this.value)">
						{html_options options=$page_content.aryStatus selected=$page_content.status}
					</select>
				</td>
				<td colspan="2" align="right">
					<input type="submit" name="save" id="save" value="Update"/>
				</td>
			</tr>
			<tr class="alt">
				<th align="left" width="33%">Poster</th>
				<th align="left" width="33%">Poster</th>
				<th align="left" width="33%">Poster</th>
			</tr>
			</thead>
			<tbody>
			<tr class="{cycle values=",alt"}">
			{foreach name=poster_list from=$page_content.poster_list item=ary_poster}
				<td>
					{*<p><strong>Like: {$ary_poster.total_like} | Viewed: {$ary_poster.total_viewed}</strong></p>*}
                    <h3>{$ary_poster.poster_name} by {$ary_poster.register_name} from {$ary_poster.state}</h3>
					<p class="poster_frame" id="smp_{$ary_poster.id}">
					<img src="{$CFG->www}image.php?s=t&amp;uniqueId={$ary_poster.uniqueId}" title="{$ary_poster.poster_name} by {$ary_poster.register_name} from {$ary_poster.state}" alt="{$ary_poster.poster_name} by {$ary_poster.register_name} from {$ary_poster.state}" width="206" height="284" />
					</p>
					<p><strong>Layout info</strong></p>
					<pre class="score_content">{$ary_poster.layout_data|@unserialize|@print_r:1}</pre>
					<p align="right">
						<a href="?section=register&amp;data={$ary_poster.register_id}&amp;search_field=id&amp;search=Search"><strong>{if $ary_poster.register_name}{$ary_poster.register_name}{else}Anonymous{/if}</strong></a>, {$ary_poster.state}, <em>{$ary_poster.datetime}</em>
						<select name="poster[{$ary_poster.id}]" id="poster_[{$ary_poster.id}]">
							{html_options options=$page_content.aryStatus selected=$ary_poster.status}
						</select>
					</p>
				</td>
				{math equation="x%3" x=$smarty.foreach.poster_list.iteration assign=step}
				{if !$step}
			</tr>
			<tr class="{cycle values=",alt"}">	
				{/if}
			{/foreach}
				{math equation="3 - x%3" x=$page_content.poster_list|@sizeof assign=step}
				{if $step < 3}
					{assign var="ary_step" value=1|@range:$step}
					{section name=td_fix loop=$ary_step}
				<td>&nbsp;</td>
					{/section}
				{/if}
			</tr>
			<tr class="">
				<td colspan="3" align="right">
					<input type="submit" name="save" id="save" value="Update"/>
				</td>
			</tr>
			</tbody>
		{/if}
		</table>
		</form>
    </div>
</div>
<script language="javascript" type="text/javascript">
	{literal}
	function fnApplyAll(name, value){
		$("select" + "[name^='"+ name +"[']").each(function(i){
			$(this).val(value);
		});
	}
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


		var text = $('#poster_row_'+currentWinnerId).text();
		$('#poster_row_'+currentWinnerId)[0].focus();
		
		var answer = confirm("Would you like to send email to:\n"+text.trim().replace(/(\s)+/g,',').replace(/\,+/g,','));

		if (answer){
			var data = {};
			data.mode	= 'send_poster_email';
			data.id		= currentWinnerId;
			fnAjaxWithInfo(data, '');
		}
		return;
	}
	
	function fnUpdateRegister(id){
		var data = {};
		data.mode	= 'edit_poster';
		data.id		= id;
		fnAjaxWithInfo(data, 'fnDisplayPanel');
	}
	
  	function fnSubmitForm(form){
  		form.submit.readonly = true;

		var data = {};
		data.mode	= 'edit_poster';
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
