{if  $USER->username}{/if}
<div class="content custom_field">
    <div class="page_content">
        <h2>{$CFG->section|capitalize:true} List<a id="page_detail" name="page_detail"></a></h2>
		<div class="tools">
			<ul>
				<li><a class="tool_icon" href="?section=csv_quiz_votes&amp;mode={$page_content.page_mode}&amp;last_download=new">Retrieve Votes as CSV [New Votes Only][{$page_content.int_csv_new}]</a></li>
				<li><a class="tool_icon" href="?section=csv_quiz_votes&amp;mode={$page_content.page_mode}&amp;last_download=all">Retrieve Votes as CSV [All Votes][{$page_content.int_csv_total}]</a></li>
			</ul>
			<div class="clear"><br class="accessibility"/></div>
		</div>
		<form class="normal_form" name="image_form" method="post" action="" >
			<input type="hidden" id="section" name="section" value="{$CFG->section}" />
			<input type="hidden" id="page" name="page" value="{$page_content.page}" />
			<input type="hidden" name="register_date" value="{$page_content.selected_date}" />
		<table class="image_list" cellpadding="2" cellspacing="2" border="0">
		{if $page_content.vote_result_list|@is_array && $page_content.vote_result_list|@sizeof}
			<tbody>
			{foreach name=smtVoteResult from=$page_content.vote_result_list item=ary_vote_item}
            <tr class="alt"><td colspan="6"><img src="{$CFG->www}images/space.gif" height="2" width="1" alt="break"/></td></tr>
            <tr class="">
				<th colspan="6" align="left">Week {$smarty.foreach.smtVoteResult.iteration} : {$ary_vote_item.question}</th>
            </tr>
			<tr id="register_row_{$ary_register_item.id}" class="">
                {foreach from=$ary_vote_item.answer key=pos item=str_answer}
                    <td width="28%">{$str_answer}</td>
                    <td width="5%" align="center">{$ary_vote_item.votes[$pos]}</td>
                {/foreach}
			</tr>
			{/foreach}
			</tbody>
		{/if}
		</table>
		</form>
    </div>
</div>
