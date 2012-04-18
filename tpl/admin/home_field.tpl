
		<table class="image_list" cellpadding="2" cellspacing="2" border="0" id="home_{$list_title_name}">
		{if $ary_register_list|@is_array && $ary_register_list|@sizeof}
			<thead>
			<tr>
				<th align="center" width="32" title="register refered by/referral email sent qty">Ref<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=ref_qty&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=ref_qty&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9</a>]</span></th>
				<th align="center">Name<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=name&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">A-Z</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=name&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">Z-A</a>]</span></th>
				<th align="center">eMail<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=email_address&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">A-Z</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=email_address&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">Z-A</a>]</span></th>
				<th align="center" width="60">Mobile<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=mobile&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=mobile&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
				<th align="center">UTM Source<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=utm_source&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=utm_source&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
				<th align="center">Referred by<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=referred_by_last_name&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">A-Z</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=referred_by_last_name&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">Z-A</a>]</span></th>
				<th align="center" width="102">Timestamp<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=timestamp&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=timestamp&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
				<th align="center" width="102">Last Download<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=last_download&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=last_download&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$ary_register_list item=ary_register_item}
			<tr id="register_row_{$ary_register_item.id}" class="{cycle values=",alt"}">
				<td align="center">{$ary_register_item.ref_qty_true}/{$ary_register_item.ref_qty}</td>
				<td>{$ary_register_item.name}</td>
				<td>{$ary_register_item.email_address}</td>
				<td>{$ary_register_item.mobile}</td>
				<td align="center">{$ary_register_item.utm_source}</td>
				<td>{if $ary_register_item.referred_by}{$ary_register_item.referred_by_name}{else}N/A{/if}</td>
				<td>{$ary_register_item.timestamp}</td>
				<td align="center" nowarp="nowarp">{if $ary_register_item.last_download == '0000-00-00 00:00:00'}N/A{else}{$ary_register_item.last_download}{/if}</td>
			</tr>
			{/foreach}
			</tbody>
		{/if}
		</table>