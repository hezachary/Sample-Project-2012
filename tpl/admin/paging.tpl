
        <div class="pagination tools {if !$page_list|is_array || $page_list|sizeof < 1}noDisplay{/if}">
			<ul class="pagination">
				{math assign=int_paging_rangeLowest equation="x" x=$int_paging_range}
				{math assign=int_paging_rangeHeighest equation="y - x + 1" x=$int_paging_range y=$page_list|@sizeof}
				{if $page_content.page > $int_paging_rangeLowest && $page_content.page < $int_paging_rangeHeighest}
					{math assign=int_paging_rangeLow equation="x - (y-1)/2" x=$page_content.page y=$int_paging_range}
					{math assign=int_paging_rangeHeigh equation="x + (y-1)/2" x=$page_content.page y=$int_paging_range}
					{math assign=intPagePrev equation="x - 1" x=$int_paging_rangeLow}
					{math assign=intPageNext equation="x + 1" x=$int_paging_rangeHeigh}
					{math assign=intPagePrevJump equation="x - (y-1)/2" x=$int_paging_rangeLow y=$int_paging_range}
					{math assign=intPageNextJump equation="x + (y-1)/2" x=$int_paging_rangeHeigh y=$int_paging_range}
				{elseif $page_content.page <= $int_paging_rangeLowest}
					{assign var=int_paging_rangeLow value=1}
					{assign var=int_paging_rangeHeigh value=$int_paging_rangeLowest}
					{assign var=intPagePrev value=0}
					{math assign=intPageNext equation="x + 1" x=$int_paging_rangeHeigh}
					{assign var=intPagePrevJump value=0}
					{math assign=intPageNextJump equation="x + (y-1)/2" x=$int_paging_rangeHeigh y=$int_paging_range}
				{elseif $page_content.page >= $int_paging_rangeHeighest}
					{assign var=int_paging_rangeLow value=$int_paging_rangeHeighest}
					{assign var=int_paging_rangeHeigh value=$page_list|@sizeof}
					{math assign=intPagePrev equation="x - 1" x=$int_paging_rangeLow}
					{assign var=intPageNext value=0}
					{math assign=intPagePrevJump equation="x - (y-1)/2" x=$int_paging_rangeLow y=$int_paging_range}
					{assign var=intPageNextJump value=0}
				{/if}
				{foreach name=topPaging from=$page_list item=intPageNum}
				{if $smarty.foreach.topPaging.first || $smarty.foreach.topPaging.last || ($intPageNum >= $int_paging_rangeLow && $intPageNum <= $int_paging_rangeHeigh)}
				<li {if $page_content.page == $intPageNum}class="selected"{/if}><a title="{$page_title_list[$smarty.foreach.topPaging.index]}" href="{$href|@mergeString:'page=':$intPageNum}">{$intPageNum}</a></li>
				{elseif $intPageNum==$intPagePrev || $intPageNum==$intPageNext}
				<li {if $page_content.page == $intPageNum}class="selected"{/if}><a title="{$page_title_list[$smarty.foreach.topPaging.index]}" href="{$href|@mergeString:'page=':$intPageNum}">...</a></li>
				{elseif $intPageNum==$intPagePrevJump}
				<li {if $page_content.page == $intPageNum}class="selected"{/if}><a title="{$page_title_list[$smarty.foreach.topPaging.index]}" href="{$href|@mergeString:'page=':$intPageNum}">&laquo;</a></li>
				{elseif $intPageNum==$intPageNextJump}
				<li {if $page_content.page == $intPageNum}class="selected"{/if}><a title="{$page_title_list[$smarty.foreach.topPaging.index]}" href="{$href|@mergeString:'page=':$intPageNum}">&raquo;</a></li>
				{/if}
				{/foreach}
				<li><form name="paging" method="get" action=""><input style="width: 50px" type="text" name="page_num"/><input type="button" name="page_jump" value="Jump To" onclick="fnPageJump(this.form.page_num.value)"/></form></li>
			</ul>
            <div class="clear">
                <br class="accessibility"/>
            </div>
        </div>