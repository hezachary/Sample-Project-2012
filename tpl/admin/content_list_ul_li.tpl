
        <li id="content_order_{$ary_content_order_item.id}">
			<p>
        	<a class="tool_icon" href="javascript:fnMove('{$ary_content_order_item.id}', 'content_order', 'Up')"><img src="{$CFG->www}images/up.gif" alt="Up" title="Up" width="11" height="10" border="0" /></a>
        	<a class="tool_icon" href="javascript:fnMove('{$ary_content_order_item.id}', 'content_order', 'Down')"><img src="{$CFG->www}images/down.gif" alt="Down" title="Down" width="11" height="10" border="0" /></a>
        	<a class="tool_icon" href="javascript:fnMove('{$ary_content_order_item.id}', 'content_order', 'Top')"><img src="{$CFG->www}images/top.gif" alt="Top" title="Top" width="11" height="10" border="0" /></a>
        	<a class="tool_icon" href="javascript:fnMove('{$ary_content_order_item.id}', 'content_order', 'Bottom')"><img src="{$CFG->www}images/bottom.gif" alt="Bottom" title="Bottom" width="11" height="10" border="0" /></a>
			{if $page_content.obj_page_content->id==$ary_content_order_item.id}
				Current Page: {$page_content.obj_page_content->title}
			{else}
				<a class="tool_icon" href="javascript:fnRemoveShortDesc('{$page_content.obj_page_content->id}','{$ary_content_order_item.id}')"><img src="{$CFG->www}images/remove.gif" alt="Remove" title="Remove" width="11" height="10" border="0" /></a>
				{assign var="tmp_id" value=$ary_content_order_item.id}
				{foreach name=content_position from=$page_content.ary_content_order_position.$tmp_id item=ary_position_item}
					{if $smarty.foreach.content_position.first}|{/if}
					{$ary_position_item.title}
					{if !$smarty.foreach.content_position.last}&gt;{/if}
				{/foreach}
			{/if}
			</p>
		</li>