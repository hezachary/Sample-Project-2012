{if  $USER->username}{/if}
<div class="content custom_field">
    <div class="page_content">
        <h2>{$CFG->section|capitalize:true} List<a id="page_detail" name="page_detail"></a></h2>
		<div class="tools">
			<ul>
				<li><a class="tool_icon" href="?section=csv_votes&amp;mode={$page_content.page_mode}&amp;last_download=new">Retrieve Votes as CSV [New Votes Only][{$page_content.int_csv_new}]</a></li>
				<li><a class="tool_icon" href="?section=csv_votes&amp;mode={$page_content.page_mode}&amp;last_download=all">Retrieve Votes as CSV [All Votes][{$page_content.int_csv_total}]</a></li>
			</ul>
			<div class="clear"><br class="accessibility"/></div>
		</div>
		<form class="normal_form" name="image_form" method="post" action="" >
			<input type="hidden" id="section" name="section" value="{$CFG->section}" />
			<input type="hidden" id="page" name="page" value="{$page_content.page}" />
			<input type="hidden" name="register_date" value="{$page_content.selected_date}" />
		<table class="image_list" cellpadding="2" cellspacing="2" border="0">
		{if $page_content.vote_result_list|@is_array && $page_content.vote_result_list|@sizeof}
			<thead>
			<tr>
				<th align="center" width="80">Sku</th>
				<th align="center" width="20">Color</th>
				<th align="center" width="80">Week 1<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=votes_week_1&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=votes_week_1&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
				<th align="center" width="80">Week 2<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=votes_week_2&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=votes_week_2&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
				<th align="center" width="80">Week 3<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=votes_week_3&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=votes_week_3&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
				<th align="center" width="80">Week 4<br/><span>[<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=votes_week_4&amp;asc=true&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">0-9</a>] [<a href="?section={$CFG->section}&amp;page={$page_content.page}&amp;order=votes_week_4&amp;asc=false&amp;data={$page_content.data}&amp;search_field={$page_content.search_field}">9-0</a>]</span></th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$page_content.vote_result_list item=ary_vote_item}
			<tr id="register_row_{$ary_register_item.id}" class="{cycle values=",alt"}">
				<td>{$ary_vote_item.sku}</td>
				<td bgcolor="#{$ary_vote_item.color}">&nbsp;</td>
				<td align="center">{$ary_vote_item.votes_week_1|@intval}</td>
				<td align="center">{$ary_vote_item.votes_week_2|@intval}</td>
				<td align="center">{$ary_vote_item.votes_week_3|@intval}</td>
				<td align="center">{$ary_vote_item.votes_week_4|@intval}</td>
			</tr>
			{/foreach}
			</tbody>
		{/if}
		</table>
		</form>
    </div>
</div>
