
			<div style="width: 1000px; margin: 10px 5px; padding: 4px 1px 1px 1px;" class="custom_field">
			<div class="page_content">
		<form class="normal_form" name="image_form" method="post" action="" >
			<input type="hidden" name="section" value="{$CFG->section}" />
		<table class="image_list" cellpadding="2" cellspacing="2" border="0">
		
		{if $page_content.register_list|@is_array && $page_content.register_list|@sizeof}
			<thead>
			<tr>
				<th align="center">Name</th>
				<th align="center">Email</th>
				<th align="center">Phone</th>
				<th align="center">Address</th>
				<th align="center">State</th>
				{if $page_content.page_mode=='reminder'}
				<th align="center">Zip</th>
				{/if}
				{if $page_content.page_mode=='campaign'}
				<th align="center">Barcode</th>
				{/if}
				<th align="center" title="3 month reminder">RM</th>
				{if $page_content.page_mode=='campaign'}
				<th align="center" title="Promotions">PM</th>
				<th align="center" title="Daily Prizes">DP</th>
				<th align="center" title="Cashback">CB</th>
				{/if}
				<th align="center" width="101">Date Time</th>
			</tr>
			</thead>
			<tbody>
			{assign var=int_valid_entry value = 0}
			{foreach from=$page_content.register_list item=ary_register_item}
			{if $ary_register_item.status=='finished'}
				{math assign=int_valid_entry equation="x + 1" x=$int_valid_entry}
			{/if}
			<tr id="register_sub_row_{$ary_register_item.id}" class="{if $ary_register_item.status!='finished'}error{/if} {cycle values=",alt"} {if $ary_register_item.is_winner=='winner'}matched_bg{/if}">
				<td>{$ary_register_item.last_name}, {$ary_register_item.first_name}
				<input type="hidden" name="sub_winner[]" id="sub_winner_{$ary_register_item.id}" value="{$ary_register_item.id}" />
				</td>
				<td>{$ary_register_item.email}</td>
				<td>{$ary_register_item.phone|replace:' ':''}</td>
				<td>{$ary_register_item.address}</td>
				<td align="center">{$ary_register_item.state}</td>
				{if $page_content.page_mode=='reminder'}
				<td align="center">{$ary_register_item.postcode}</td>
				{/if}
				{if $page_content.page_mode=='campaign'}
				<td>{$ary_register_item.barcode}</td>
				{/if}
				<td align="center">{$ary_register_item.reminder}</td>
				{if $page_content.page_mode=='campaign'}
				<td align="center">{$ary_register_item.promotions}</td>
				<td align="center">{$ary_register_item.daily_prizes}</td>
				<td align="center">{$ary_register_item.cashback}</td>
				{/if}
				<td align="center" nowarp="nowarp">{$ary_register_item.timestamp}</td>
			</tr>
			{/foreach}
			</tbody>
			{if $page_content.page_mode=='campaign'}
			<tfoot>
			<tr>
				<td align="right" colspan="11">
					<input type="button" name="random" value="Re-Pick 5 Winners Randomly" onclick="fnPickWinners()" />
					<input type="button" style="width: 200px;" name="update_daily_winner" value="Update Daily Winner" onclick="fnUpdateWinners(this.form)" />
				</td>
			</tr>
			{/if}
			</tfoot>
		{/if}
		</table>
		</form>
		</div>
			</div>