{if  $USER->username}{/if}
{include file=reg_charts.tpl}
<div class="content custom_field">
    <div class="page_content">
        <h2>General Info<a id="page_detail" name="page_detail"></a></h2>
        <table class="image_list" cellpadding="2" cellspacing="2" border="0">
			<thead>
        	<tr>
				<th>Today Register</th>
				<th>Yesterday Register</th>
				<th>Last 7 Days Register</th>
				<th>Last 30 Days Register</th>
				<th>Last Month Register</th>
				<th>Total Register</th>
				<th>Total Registed By Referral</th>
				<th>Total Referral and Schedule Email Sent</th>
			</tr>
			</thead>
			<tbody>
        	<tr>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data={$smarty.now|date_format:'%Y-%m-%d'}~{$smarty.now|date_format:'%Y-%m-%d'}&amp;search_field=timestamp&amp;search=Search">{$page_content.today_reg}</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data={'-1 day'|@strtotime|date_format:'%Y-%m-%d'}&amp;search_field=timestamp&amp;search=Search">{$page_content.last_1_day_reg}</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data={'-7 day'|@strtotime|date_format:'%Y-%m-%d'}~&amp;search_field=timestamp&amp;search=Search">{$page_content.last_7_days_reg}</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data={'-1 month'|@strtotime|date_format:'%Y-%m-%d'}~&amp;search_field=timestamp&amp;search=Search">{$page_content.last_30_days_reg}</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data={'-1 month'|@strtotime|date_format:'%Y-%m'}-01~{'Y-m'|@date|@mergeString:'-1 -1 day'|@strtotime|date_format:'%Y-%m-%d'}&amp;search_field=timestamp&amp;search=Search">{$page_content.last_month_reg}</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp">{$page_content.total_reg}</a></td>
				<td align="center"><a href="?section=register&amp;page=1&amp;order=timestamp&amp;asc=false&amp;data=ISNOTNULL&amp;search_field=referred_by&amp;search=Search">{$page_content.total_reg_ref}</a></td>
				<td align="center">{$page_content.total_ref_email} <a href="?section=csv_email_log&amp;mode=log_only">[Email Log - CSV]</a> <a href="?section=csv_email_log&amp;mode=email_log">[Register with Email Log - CSV]</a> <a href="?section=csv_email_log&amp;mode=register">[Group Count - CSV]</a></td>
			</tr>
			</tbody>
		</table>
        {foreach from=$page_content.list item=ary_register_list key=list_title_name}
        <h2>{$list_title_name|replace:'_':' '|capitalize:true}<a id="page_detail" name="page_detail"></a> <a href="javascript:fnReloadField('{$list_title_name}');">[Refresh]</a></h2>
        {include file='home_field.tpl' ary_register_list=$ary_register_list}
		{/foreach}
    </div>
</div>